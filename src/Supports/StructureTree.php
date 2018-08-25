<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2018/8/24
 * Time: 下午4:04
 */

namespace Touge\Scaffold\Supports;


class StructureTree{
    /**
     * 主键
     * @var string
     */
    protected $primary_key              = 'id';

    /**
     * 父键
     * @var string
     */
    protected $parent_key               = 'parent_id';

    /**
     * 获得到的指定级别
     * @var int
     */
    protected $level                    = 0;

    /**
     * 展开属性
     *
     * @var string
     */
    protected $expanded_key             = 'expanded';

    /**
     * 叶子节点属性
     *
     * @var string
     */
    protected  $leaf_key                = 'leaf';

    /**
     * 子节点属性
     * @var string
     */
    protected $children_key             = 'children';

    /**
     * 是否展开子节点
     * @var bool
     */
    protected $expanded                 = false;

    /**
     * 结果集
     * @var array
     */
    protected static  $_result          = [];

    /**
     * 层次暂存
     * @var array
     */
    protected static $_level            = [];


    /**
     * @param array $params
     * @param array $options
     * @return array
     */
    public function make(Array $params,$options=array()){
        if(!$params) return [];
        $options = $this->buildData($params,$options);
        $result = $this->core(0,$options,'normal');
        return $result;
    }


    /**
     * 根据子ID，寻找父级集
     *
     * @param $params
     * @param $son_id
     * @return array
     */
    public function find_parents(Array $params,$son_id){
        if(!$params) return [];
        static $list;
        foreach($params as $v) {
            if($v[$this->primary_key] == $son_id) {
                $list[] =$v;
                $this->find_parents($params,$v[$this->parent_key]);
            }
        }
        return $list;
    }

    /**
     * 生成线性一维数据结构, 便于HTML输出, 参数同上
     *
     * @param array $params
     * @param int $level
     * @return array
     */
    public function html_list(Array $params,$level=-1){
        if(!$params) return [];
        $options = $this->buildData($params);
        $options = $this->core(0,$options,'linear');
        if($level==-1) return $options;

        foreach($options as $key=>$val){
            if($val['level'] > $level){
                unset($options[$key]);
            }
        }
        return $options;
    }

    /**
     * 生成树核心, 私有方法
     * @param $index
     * @param array $params
     * @param string $type
     * @return array
     */
    private function core($index,Array $params,$type='linear'){
        $result = [];
        foreach($params[$index] as $id=>$item) {
            if($type=='normal'){
                if(isset($params[$id]))
                {
                    $item[$this->expanded_key]= $this->expanded;//self::$config['expanded'];
                    $item[$this->children_key]= $this->core($id,$params,$type);
                } else {
                    $item[$this->leaf_key]= true;
                }
                $result[] = $item;
            }else if($type=='linear'){
                $parent_id = $item[$this->parent_key];
                self::$_level[$id] = $index==0 ? 0 : self::$_level[$parent_id]+1;
                $item['level'] = self::$_level[$id];
                self::$_result[] = $item;
                if(isset($params[$id])) {
                    $this->core($id,$params,$type);
                }
                $result = self::$_result;
            }
        }
        return $result;
    }

    /**
     * 格式化数据, 私有方法
     * @param $params
     * @return array
     */
    private function buildData(Array $params){
        $result = [];
        foreach($params as $item)
        {
            $id = $item[$this->primary_key];
            $parent_id = $item[$this->parent_key];
            $result[$parent_id][$id] = $item;
        }
        return $result;
    }
}




