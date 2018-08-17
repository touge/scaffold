<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2018/8/17
 * Time: 下午8:04
 */
namespace Touge\Scaffold\Facades;

use Illuminate\Support\Facades\Facade;

class ClassificationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'classification';
    }
}