<?php namespace Bugotech\Foundation\Console\Commands;

use Bugotech\IO\Filesystem;
use Illuminate\Console\Command;

class MaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env {opt : Option on or off }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Turn on or off app in maintenance';

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new service provider instance.
     *
     * @param \Bugotech\IO\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $opt = strtolower($this->argument('opt'));
        $file = storage_path('framework/.maintenance');

        // Verificar se diretorio existe
        $this->files->force($this->files->path($file));

        // Colocar em menutencao
        if ($opt == 'off') {
            touch($file);
            $this->comment('Application is now in maintenance mode.');
        } else {
            $this->files->delete($file);
            $this->info('Application is now live.');
        }
    }
}
