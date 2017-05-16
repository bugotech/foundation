<?php namespace Bugotech\Foundation\Console\Commands;

use Illuminate\Console\Command;

class MaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance {opt=on : Option on or off }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Turn on or off app in maintenance';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $opt = strtolower($this->argument('opt'));
        $file = storage_path('/framework/.maintenance');

        // Colocar em menutencao
        if ($opt == 'on') {
            touch($file);
            $this->comment('Application is now in maintenance mode.');
        } else {
            @unlink($file);
            $this->info('Application is now live.');
        }
    }
}
