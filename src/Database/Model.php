<?php namespace Bugotech\Foundation\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    use ValidatorModel;

    public $timestamps = false;
}