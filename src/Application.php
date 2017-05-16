<?php namespace Bugotech\Foundation;

use Monolog\Logger;
use RuntimeException;
use Illuminate\Support\Str;
use Bugotech\IO\Filesystem;
use Illuminate\Support\Composer;
use Monolog\Handler\StreamHandler;
use Illuminate\Container\Container;
use Monolog\Formatter\LineFormatter;
use Illuminate\Support\ServiceProvider;
use Bugotech\Foundation\Console\Artisan;
use Illuminate\Config\Repository as ConfigRepository;

class Application extends Container
{
    /**
     * The base path of the application installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * All of the loaded configuration files.
     *
     * @var array
     */
    protected $loadedConfigurations = [];

    /**
     * The loaded service providers.
     *
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * The application namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Create a new Lumen application instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    public function __construct($basePath = null)
    {
        date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

        $this->basePath = $basePath;

        $this->bootstrapContainer();
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        return 'NetForce (5.2)';
    }

    /**
     * Bootstrap the application container.
     *
     * @return void
     */
    protected function bootstrapContainer()
    {
        static::setInstance($this);

        $this->instance('app', $this);
        $this->instance('Bugotech\Foundation\Application', $this);

        $this->registerContainerBase();
        $this->registerContainerAliases();

        $this->configure('app');
    }

    /**
     * Determine if the application is currently down for maintenance.
     *
     * @return bool
     */
    public function isDownForMaintenance()
    {
        return false;
    }

    /**
     * Get or check the current application environment.
     *
     * @param  mixed
     * @return string
     */
    public function environment()
    {
        $env = env('APP_ENV', 'production');

        if (func_num_args() > 0) {
            $patterns = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();

            foreach ($patterns as $pattern) {
                if (Str::is($pattern, $env)) {
                    return true;
                }
            }

            return false;
        }

        return $env;
    }

    /**
     * Register a service provider with the application.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register($provider)
    {
        if (! $provider instanceof ServiceProvider) {
            $provider = new $provider($this);
        }

        if (array_key_exists($providerName = get_class($provider), $this->loadedProviders)) {
            return;
        }

        $this->loadedProviders[$providerName] = true;

        if (method_exists($provider, 'register')) {
            $provider->register();
        }

        if (method_exists($provider, 'boot')) {
            return $this->call([$provider, 'boot']);
        }

        return null;
    }

    /**
     * Configure and load the given component and provider.
     *
     * @param  string  $config
     * @param  array|string  $providers
     * @param  string|null  $return
     * @return mixed
     */
    public function loadComponent($config, $providers, $return = null)
    {
        $this->configure($config);

        foreach ((array) $providers as $provider) {
            $this->register($provider);
        }

        return $this->make($return ?: $config);
    }

    /**
     * Load a configuration file into the application.
     *
     * @param  string  $name
     * @return void
     */
    public function configure($name)
    {
        if (isset($this->loadedConfigurations[$name])) {
            return;
        }

        $this->loadedConfigurations[$name] = true;

        $path = $this->getConfigurationPath($name);

        if ($path) {
            $this->make('config')->set($name, require $path);
        }
    }

    /**
     * Get the path to the given configuration file.
     *
     * If no name is provided, then we'll return the path to the config folder.
     *
     * @param  string|null  $name
     * @return string
     */
    public function getConfigurationPath($name = null)
    {
        if (! $name) {
            $appConfigDir = $this->basePath('config').'/';

            if (file_exists($appConfigDir)) {
                return $appConfigDir;
            } elseif (file_exists($path = __DIR__.'/../config/')) {
                return $path;
            }
        } else {
            $appConfigPath = $this->basePath('config').'/'.$name.'.php';

            if (file_exists($appConfigPath)) {
                return $appConfigPath;
            } elseif (file_exists($path = __DIR__.'/../config/'.$name.'.php')) {
                return $path;
            }
        }

        return '';
    }

    /**
     * Get the base path for the application.
     *
     * @param  string|null  $path
     * @return string
     */
    public function basePath($path = null)
    {
        if (isset($this->basePath)) {
            return $this->basePath.($path ? '/'.$path : $path);
        }

        if ($this->runningInConsole()) {
            $this->basePath = getcwd();
        } else {
            $this->basePath = realpath(getcwd().'/../');
        }

        return $this->basePath($path);
    }

    /**
     * Return path of type.
     *
     * @param $type
     * @param null $path
     * @return string
     */
    public function path($type, $path = null)
    {
        if (array_key_exists($type, $this->instances)) {
            return $this->instances[$type];
        }

        $path = is_null($path) ? $type : $type . DIRECTORY_SEPARATOR . $path;

        return $this->basePath($path);
    }

    /**
     * Determine if the application is running in the console.
     *
     * @return bool
     */
    public function runningInConsole()
    {
        return php_sapi_name() == 'cli';
    }

    /**
     * Determine if we are running unit tests.
     *
     * @return bool
     */
    public function runningUnitTests()
    {
        return $this->environment() == 'testing';
    }

    /**
     * Get the application namespace.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getNamespace()
    {
        if (! is_null($this->namespace)) {
            return $this->namespace;
        }

        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array) $path as $pathChoice) {
                if (realpath(app()->path('app')) == realpath(base_path().'/'.$pathChoice)) {
                    return $this->namespace = $namespace;
                }
            }
        }

        throw new RuntimeException('Unable to detect application namespace.');
    }

    /**
     * Register the core conteiner base structures.
     *
     * @return void
     */
    protected function registerContainerBase()
    {
        // Files
        $this->singleton('files', function () {
            return new Filesystem();
        });

        // Composer
        $this->singleton('composer', function ($app) {
            return new Composer($app->make('files'), $this->basePath());
        });

        // Config
        $this->singleton('config', function () {
            return new ConfigRepository();
        });

        // Log
        $this->singleton('log', function () {
            return new Logger('netforce', [$this->getMonologHandler()]);
        });

        // Events
        $this->register('\Illuminate\Events\EventServiceProvider');

        // Artisan - Console
        $this->singleton('artisan', function ($app) {
            $artisan = new Artisan($app, $app->make('events'), $app->version());
            $artisan->resolveCommands([]);

            return $artisan;
        });

        // Cache
        $this->singleton('cache', function () {
            return $this->loadComponent('cache', 'Illuminate\Cache\CacheServiceProvider');
        });
        $this->singleton('cache.store', function () {
            return $this->loadComponent('cache', 'Illuminate\Cache\CacheServiceProvider', 'cache.store');
        });
    }

    /**
     * Register the core container aliases.
     *
     * @return void
     */
    protected function registerContainerAliases()
    {
        $this->aliases = [
            'Illuminate\Contracts\Foundation\Application' => 'app',
            'Illuminate\Contracts\Container\Container' => 'app',
            'Illuminate\Container\Container' => 'app',
            'Illuminate\Contracts\Config\Repository' => 'config',
            'Illuminate\Contracts\Console\Kernel' => 'artisan',
            'Illuminate\Contracts\Events\Dispatcher' => 'events',
            //'Illuminate\Database\ConnectionResolverInterface' => 'db',
            //'Illuminate\Database\DatabaseManager' => 'db',
            //'Illuminate\Contracts\Encryption\Encrypter' => 'encrypter',
            //'Illuminate\Contracts\Hashing\Hasher' => 'hash',
            //'Illuminate\Contracts\Queue\Factory' => 'queue',
            //'Illuminate\Contracts\Queue\Queue' => 'queue.connection',
            //'request' => 'Illuminate\Http\Request',
            //'Laravel\Lumen\Routing\UrlGenerator' => 'url',
            //'Illuminate\Contracts\Validation\Factory' => 'validator',
            //'Illuminate\Contracts\View\Factory' => 'view',
        ];
    }

    /**
     * Get the Monolog handler for the application.
     *
     * @return \Monolog\Handler\AbstractHandler
     */
    protected function getMonologHandler()
    {
        return (new StreamHandler($this->path('storage', 'logs/netforce.log'), Logger::DEBUG))->setFormatter(new LineFormatter(null, null, true, true));
    }
}
