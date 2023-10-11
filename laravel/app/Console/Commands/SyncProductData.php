<?php

namespace App\Console\Commands;

use App\Services\SyncProductData as ServicesSyncProductData;
use Illuminate\Console\Command;

class SyncProductData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync-products-data';

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
        info('start time export ' . now());
        ServicesSyncProductData::syncProduct($auto=true);
        info('end time ' . now());
    }
}
