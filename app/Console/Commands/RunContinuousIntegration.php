<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

final class RunContinuousIntegration extends Command
{
    /**
     * @var string[]
     */
    private const array CI_CHECKS = [
        'composer run ci',
        'composer audit',
        'npm run check',
        'npm audit',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-ci';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Runs CI checks for the project.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Running CI checks...');

        foreach (self::CI_CHECKS as $command) {
            $this->info("Running: '$command'");
            $process = Process::start($command, function (string $type, string $output): void {
                echo $output;
            });

            $result = $process->wait();

            if ($result->successful()) {
                $this->info("Command '$command' succeeded!");
            } elseif ($result->failed()) {
                $this->error("Command '$command' failed!");

                return self::FAILURE;
            }
        }

        return self::SUCCESS;
    }
}
