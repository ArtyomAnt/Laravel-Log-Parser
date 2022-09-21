<?php

namespace App\Console\Commands;

use App\Services\LogReader;
use Illuminate\Console\Command;

class LogParser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:parser
                            {--P|path= : The path to data logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the log file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if (!$this->option('path')) {
            $this->error('You need to specify PATH for your log!');
            return 0;
        }

        $parser = new LogReader($this->option('path'));
        $parser->readLogAndDispatchMessages();

        return 1;
    }
}
