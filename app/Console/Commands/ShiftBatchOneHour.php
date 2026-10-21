<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ShiftBatchOneHour extends Command
{
    protected $signature = 'shift:batch-one-hour';

    protected $description = 'Deprecated unsafe DST command';

    public function handle(): int
    {
        $this->error('This command is disabled because it can shift batches without date, audit, or duplicate protection.');
        $this->line('Use batches:dst-adjust {region} {transition} --dry-run first.');

        return self::FAILURE;
    }
}
