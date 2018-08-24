<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2018/8/24
 * Time: 下午4:06
 */

namespace Touge\Scaffold\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class StructureTree
 * @method static array make(Array $params,$options=array())
 * @method static array find_parents(Array $params,$son_id)
 * @method static array html_list(Array $params,$options=[])
 */
class StructureTree extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'structure.tree';
    }
}