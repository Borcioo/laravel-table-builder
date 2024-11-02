<?php

namespace Borek\LaravelTableBuilder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallComponentsCommand extends Command
{
    protected $signature = 'table-builder:install-components';
    protected $description = 'Copies React components to the project';

    private $componentPaths = [
        'components' => [
            'source' => __DIR__ . '/../../../resources/js/Components/TableBuilder',
            'destination' => 'resources/js/Components/TableBuilder'
        ],
    ];

    public function handle()
    {
        $this->info('Installing TableBuilder components...');

        foreach ($this->componentPaths as $type => $paths) {
            $this->copyFiles($paths['source'], $paths['destination'], $type);
        }

        $this->info('Components have been installed!');
        $this->info('You can now import components from:');
        $this->line('import { DataTable } from "@/Components/TableBuilder"');
    }

    private function copyFiles($source, $destination, $type)
    {
        if (!File::isDirectory($source)) {
            $this->error("Source directory not found for: {$type}");
            return;
        }

        $destinationPath = base_path($destination);

        // Create destination directory if it doesn't exist
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // Copy files
        File::copyDirectory($source, $destinationPath);

        $this->info("Copied {$type} to {$destination}");
    }
}
