<?php

namespace Gtd\Suda\Facades;

use Illuminate\Support\Facades\Facade;

class Sudacore extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return 'sudacore';
    }
}
