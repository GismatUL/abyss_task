<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class DeleteTaskRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Task::whereDate( 'created_at', '<=', now()->subDays(30))->delete();
    }
}
