<?php namespace Bugotech\Foundation\Support;

use Bugotech\Support\Str;

class Attributes
{
    use Validates;

    /**
     * @var string
     */
    protected $methodNameGetMutator = 'get%sAttribute';

    /**
     * @var string
     */
    protected $methodNameSetMutator = 'set%sAttribute';

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Fill the model attributes.
     * @param array $attributes
     */
    public function fill(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Return attribute key.
     * @param $key
     * @return mixed|null
     */
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->getAttributeValue($key);
        }

        //return $this->getRelationValue($key);
    }

    /**
     * Return attribute key value.
     * @param $key
     * @return mixed|null
     */
    public function getAttributeValue($key)
    {
        $value = $this->getAttributeFromArray($key);

        // Verificar se tem mutator
        if ($this->hasGetMutator($key)) {
            $method = sprintf($this->methodNameGetMutator, Str::studly($key));

            return call_user_func_array([$this, $method], [$value]);
        }

        // If the attribute exists within the cast array, we will convert it to
        // an appropriate native PHP type dependant upon the associated value
        // given with the key in the pair. Dayle made this comment line up.
        //if ($this->hasCast($key)) {
        //    return $this->castAttribute($key, $value);
        //}

        // If the attribute is listed as a date, we will convert it to a DateTime
        // instance on retrieval, which makes it quite convenient to work with
        // date fields without having to create a mutator for each property.
        //if (in_array($key, $this->getDates()) && ! is_null($value)) {
        //    return $this->asDateTime($value);
        //}

        return $value;
    }

    /**
     * Return attribute key.
     * @param $key
     * @return mixed|null
     */
    protected function getAttributeFromArray($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
    }

    /**
     * Set attribute key and value.
     * @param $key
     * @param $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        if ($this->hasSetMutator($key)) {
            $method = sprintf($this->methodNameSetMutator, Str::studly($key));

            return $this->{$method}($value);
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Return if attribute has method get.
     * @param $key
     * @return bool
     */
    protected function hasGetMutator($key)
    {
        return method_exists($this, sprintf($this->methodNameGetMutator, Str::studly($key)));
    }

    /**
     * Return if attribute has method set.
     * @param $key
     * @return bool
     */
    protected function hasSetMutator($key)
    {
        return method_exists($this, sprintf($this->methodNameSetMutator, Str::studly($key)));
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }
}