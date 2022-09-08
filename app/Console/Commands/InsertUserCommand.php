<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\InsertUserController;
use Illuminate\Console\Command;

class InsertUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:insertusers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consumo del API para insertar usuarios';

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
     * @return int
     */
    public function handle()
    {
        $insertdata = new InsertUserController();
        $insertdata->getUsers();
        return 0;
    }
}
