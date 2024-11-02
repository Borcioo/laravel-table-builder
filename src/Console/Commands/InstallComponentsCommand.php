<?php

namespace Borek\LaravelTableBuilder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class InstallComponentsCommand extends Command
{
    protected $signature = 'table-builder:install-components {--path= : Custom path for components}';
    protected $description = 'Copies React components to the project';

    private function getComponentPath(): string
    {
        $customPath = $this->option('path');

        if (File::exists(config_path('table-builder.php'))) {
            $configPath = config('table-builder.components_path');
        } else {
            $configPath = Config::get('table-builder.default_path');
        }

        $basePath = 'resources/js';
        $destinationPath = $customPath
            ? "{$basePath}/{$customPath}"
            : "{$basePath}/{$configPath}";

        if (File::isDirectory(base_path($destinationPath))) {
            $this->warn("Directory {$destinationPath} already exists!");

            if (!$this->confirm('Do you want to specify a different path?', true)) {
                if (!$this->confirm('This will overwrite existing files. Are you sure?', false)) {
                    $this->info('Installation cancelled.');
                    exit;
                }
                return $destinationPath;
            }

            do {
                $newPath = $this->ask('Enter new path (relative to resources/js):', 'Components/TableBuilder_new');
                $fullPath = "{$basePath}/{$newPath}";

                if (!File::isDirectory(base_path($fullPath))) {
                    break;
                }

                $this->warn("Directory {$fullPath} also exists!");
            } while (true);

            $this->updateConfig($newPath);
            return $fullPath;
        }

        return $destinationPath;
    }

    private function updateConfig(string $newPath): void
    {
        $configPath = config_path('table-builder.php');
        if (File::exists($configPath)) {
            $config = File::get($configPath);
            $config = preg_replace(
                "/'components_path' => '.*?'/",
                "'components_path' => '{$newPath}'",
                $config
            );
            File::put($configPath, $config);
        }
    }

    public function handle()
    {
        if (!File::exists(config_path('table-builder.php'))) {
            if ($this->confirm('Would you like to publish the config file?', true)) {
                $this->call('vendor:publish', [
                    '--tag' => 'table-builder-config'
                ]);

                $this->info('Configuration file published successfully!');
            }
        }

        $this->info('Installing TableBuilder components...');

        $destinationPath = $this->getComponentPath();
        $source = __DIR__ . '/../../../resources/js/Components/TableBuilder';

        if (!File::isDirectory($source)) {
            $this->error("Source directory not found!");
            return;
        }

        if (!File::isDirectory(base_path($destinationPath))) {
            File::makeDirectory(base_path($destinationPath), 0755, true);
        }

        File::copyDirectory($source, base_path($destinationPath));

        $componentName = basename($destinationPath);

        $this->info('Components have been installed!');
        $this->info('You can now import components from:');
        $this->line("import { DataTable } from \"@/Components/{$componentName}\"");
    }
}
