<?php namespace Bugotech\Foundation\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    use CastModel;
    use ValidatorModel;
    use DefaultValuesModel;

    public $timestamps = false;

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->fireModelEvent('loading', false);

        parent::__construct($attributes);

        $this->fireModelEvent('loaded', false);
    }

    /**
     * Register a loading model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @param  int  $priority
     * @return void
     */
    public static function loading($callback, $priority = 0)
    {
        static::registerModelEvent('loading', $callback, $priority);
    }

    /**
     * Register a loaded model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @param  int  $priority
     * @return void
     */
    public static function loaded($callback, $priority = 0)
    {
        static::registerModelEvent('loaded', $callback, $priority);
    }
}