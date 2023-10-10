<?php

namespace App\Console\Commands;

use App\Services\Export;
use Illuminate\Console\Command;

class ExportProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export-product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        info('start time export '. now());
        Export::exportProductCSVFile();
        info('end time '. now());
    }
}
