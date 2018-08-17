<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2018/8/17
 * Time: 下午8:01
 */

namespace Touge\Scaffold\Services;

/**
 *
 * data:[
 *  ['id','parent_id'....]
 *  ['id','parent_id'....]
 *  ['id','parent_id'....]
 * ]
 *
 * Class ClassificationFacade
 * @package Touge\scaffold\Services
 */
class ClassificationService
{
    /**
     * 递归获取数据
     *
     * @param $collection
     * @param string $parentId
     * @param null $item
     * @param string $name
     * @return array
     */
    public function tree(&$collection, $parentId = '0', &$item = null, $name = 'children'){
        $tree = [];
        foreach ($collection as $key => $value) {
            if ($value['parent_id'] == $parentId) {
                $this->shiftCollection($collection, $value, $key);
                if ($item){
                    $item[$name][] = $value;
                } else {
                    $tree[] = $value;
                }
            }
        };
        return $tree;
    }

    /**
     * 删除分配的元素
     *
     * @param $key
     * @param $collection
     * @param $value
     */
    private function shiftCollection( &$collection, &$value, $key){
        unset($collection[$key]);
        $this->tree($collection, $value['id'], $value);
    }
}