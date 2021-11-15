<?php

namespace BetterFly\Skeleton\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class DatabaseReset extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betterfly:db_reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets Database';

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
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed', ['--class' => 'BetterFly\\Skeleton\\Database\\Seeds\\DatabaseSeeder']);

        $this->info('Database Reset Successfuly Completed');
    }
}
