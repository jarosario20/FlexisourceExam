<?php

namespace App\Console\Commands;

use App\Importer;
use Illuminate\Console\Command;

class getCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getCustomers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get customers.';

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
    public function handle()
    {
        $url = 'https://randomuser.me/api/?results=5000';
        $nat = 'au';
        $minimumUser = 100;

        return Importer::import($url, $minimumUser, $nat);
    }

}