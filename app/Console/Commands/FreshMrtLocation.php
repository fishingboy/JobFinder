<?php

namespace App\Console\Commands;

use App\Services\MrtService;
use Illuminate\Console\Command;

class FreshMrtLocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mrt:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(MrtService $mrtService)
    {
        if ($mrtService->getLocationWithGeo())
        {
            $this->info("get mrt location success");
        }
        else
        {
            $this->info("get mrt location failed");
        }
    }
}
