<?php

namespace Borek\LaravelTableBuilder\Console\Commands;

use Illuminate\Console\Command;

class InstallDependenciesCommand extends Command
{
    protected $signature = 'table-builder:install-dependencies';
    protected $description = 'Installs required npm dependencies';

    private $dependencies = [
        'lucide-react' => '^0.453.0',
        'lodash' => '^4.17.21',
        '@tanstack/react-table' => '^4.17.12',
    ];

    private $devDependencies = [
        '@types/lodash' => '^4.17.12',
    ];

    private $requiredShadcnComponents = [
        'badge' => 'Badge',
        'input' => 'Input',
        'label' => 'Label',
        'table' => 'Table',
        'scroll-area' => 'ScrollArea',
        'popover' => 'Popover',
        'select' => 'Select',
    ];

    private string $selectedPackageManager;

    public function handle()
    {
        $this->info('Checking and installing dependencies...');

        $this->selectedPackageManager = $this->choice(
            'Select preferred package manager:',
            ['npm', 'pnpm', 'yarn'],
            $this->detectPackageManager()
        );

        $this->info("Selected package manager: {$this->selectedPackageManager}");

        $packageJson = $this->getPackageJson();
        $missingDependencies = $this->getMissingDependencies($packageJson);

        if (!empty($missingDependencies)) {
            if ($this->confirm('Do you want to install required dependencies?', true)) {
                if (!$this->installDependencies($missingDependencies)) {
                    return 1;
                }
            }
        }

        if (!$this->isShadcnInstalled()) {
            $this->warn('shadcn/ui not detected in the project.');
            if ($this->confirm('Do you want to install shadcn/ui?', true)) {
                $this->info('Running shadcn/ui installer...');
                if (!$this->installShadcn()) {
                    return 1;
                }
                if (!$this->handleShadcnComponents()) {
                    return 1;
                }
            } else {
                $this->warn('Warning: Some components may not work without shadcn/ui!');
            }
        } else {
            $this->info('shadcn/ui is already installed.');
            if (!$this->handleShadcnComponents()) {
                return 1;
            }
        }

        return 0;
    }

    private function isShadcnInstalled(): bool
    {
        return file_exists(base_path('components.json'));
    }

    private function installShadcn()
    {
        $command = match ($this->selectedPackageManager) {
            'pnpm' => 'pnpm dlx shadcn@latest init',
            default => 'npx shadcn@latest init'
        };

        $this->info("Running: $command");
        $this->warn('Please follow the shadcn installer instructions...');

        passthru($command);

        return true;
    }

    private function getPackageJson(): array
    {
        $path = base_path('package.json');
        return json_decode(file_get_contents($path), true);
    }

    private function getMissingDependencies(array $packageJson): array
    {
        $installed = array_merge(
            $packageJson['dependencies'] ?? [],
            $packageJson['devDependencies'] ?? []
        );

        return array_filter(
            array_merge($this->dependencies, $this->devDependencies),
            fn($version, $package) => !isset($installed[$package]),
            ARRAY_FILTER_USE_BOTH
        );
    }

    private function installDependencies(array $dependencies)
    {
        foreach ($dependencies as $package => $version) {
            $this->info("Installing {$package}@{$version}");

            $isDevDependency = array_key_exists($package, $this->devDependencies);

            $command = match ($this->selectedPackageManager) {
                'yarn' => "yarn add " . ($isDevDependency ? "-D " : "") . "{$package}@{$version}",
                'pnpm' => "pnpm add " . ($isDevDependency ? "-D " : "") . "{$package}@{$version}",
                default => "npm install " . ($isDevDependency ? "--save-dev " : "") . "{$package}@{$version}"
            };

            $result = shell_exec($command);

            if ($result === null) {
                $this->error("Error installing {$package}");
                if (!$this->confirm('Do you want to continue installation?', true)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function detectPackageManager(): string
    {
        if (file_exists(base_path('pnpm-lock.yaml'))) {
            return 'pnpm';
        }

        if (file_exists(base_path('yarn.lock'))) {
            return 'yarn';
        }

        return 'npm';
    }

    private function handleShadcnComponents()
    {
        if (!$this->isShadcnInstalled()) {
            return false;
        }

        $this->info('Checking required shadcn/ui components...');

        $missingComponents = $this->getMissingShadcnComponents();

        if (empty($missingComponents)) {
            $this->info('All required shadcn components are already installed.');
            return true;
        }

        $this->info('Select components to install:');

        $choices = array_map('strtolower', array_values($missingComponents));
        array_unshift($choices, 'all');

        $selectedComponents = $this->choice(
            question: 'Select components to install (space to select, enter to confirm):',
            choices: $choices,
            multiple: true,
        );

        if (in_array('all', $selectedComponents)) {
            $selectedComponents = array_map('strtolower', array_keys($missingComponents));
        } else {
            $selectedComponents = array_filter($selectedComponents, fn($item) => $item !== 'all');
        }

        if (!empty($selectedComponents)) {
            $this->installShadcnComponents($selectedComponents);
        }

        return true;
    }

    private function getMissingShadcnComponents(): array
    {
        $missing = [];
        $componentsPath = base_path('components/ui');

        foreach ($this->requiredShadcnComponents as $command => $name) {
            if (!file_exists("{$componentsPath}/{$command}.tsx")) {
                $missing[$command] = $name;
            }
        }

        return $missing;
    }

    private function installShadcnComponents(array $components)
    {
        foreach ($components as $component) {
            $command = match ($this->selectedPackageManager) {
                'pnpm' => "pnpm dlx shadcn@latest add {$component}",
                default => "npx shadcn@latest add {$component}"
            };

            $this->info("Installing shadcn/ui component: {$component}");

            $result = passthru($command);

            $this->line("Exit code: {$result}");

            if ($result === 1) {
                $this->error("Error installing component {$component}");
                if (!$this->confirm('Do you want to continue installation?', true)) {
                    return false;
                }
            } else {
                $this->info("Component {$component} has been successfully installed.");
            }

            sleep(1);
        }

        return true;
    }
}
