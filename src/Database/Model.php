<?php namespace Bugotech\Foundation\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    use CastModel;
    use ValidatorModel;

    public $porInquilino = false;

    public $timestamps = false;
}