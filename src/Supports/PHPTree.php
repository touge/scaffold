<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2018/8/24
 * Time: 下午4:57
 */

namespace Touge\Scaffold\Supports;


class PHPTree{

    protected static $config = array(
        /* 主键 */
        'primary_key' 	=> 'id',
        /* 父键 */
        'parent_key'  	=> 'parent_id',
        /*获得到的指定级别*/
        'level' => 0,
        /* 展开属性 */
        'expanded_key'  => 'expanded',
        /* 叶子节点属性 */
        'leaf_key'      => 'leaf',
        /* 孩子节点属性 */
        'children_key'  => 'childs',
        /* 是否展开子节点 */
        'expanded'    	=> false
    );

    /* 结果集 */
    protected static $result = array();

    /* 层次暂存 */
    protected static $level = array();

    /* 生成线性结构, 便于HTML输出, 参数同上 */
    public function makeTreeForHtml($data,$options=array())
    {
        $dataset = $this->_buildData($data,$options);
        $result_array = $this->core(0,$dataset,'linear');

        $_level = self::$config['level'];
        if($_level == 0) return $result_array;

        $_result_array = [];
        foreach($result_array as $key=>$val){
            if($val['level'] <= $_level)
            {
                array_push($_result_array,$val);
            }
        }
        return $_result_array;
    }

    protected $primary_key = 'id';
    protected $parent_key = 'parent_id';

    /**
     * 生成树核心, 私有方法
     * @param $index
     * @param array $params
     * @param string $type
     * @return array
     */
    private function core($index,Array $params,$type='linear'){
        foreach($params[$index] as $id=>$item)
        {
            if($type=='normal'){
                if(isset($params[$id]))
                {
                    $item[$this->expanded_key]= self::$config['expanded'];
                    $item[$this->children_key]= self::makeTreeCore($id,$params,$type);
                }
                else
                {
                    $item[$this->leaf_key]= true;
                }
                $r[] = $item;
            }else if($type=='linear'){
                $parent_id = $item[$this->parent_key];
                self::$level[$id] = $index==0?0:self::$level[$parent_id]+1;
                $item['level'] = self::$level[$id];
                self::$result[] = $item;
                if(isset($params[$id]))
                {
                    $this->core($id,$params,$type);
                }
                $r = self::$result;
            }
        }
        return $r;
    }

    /**
     * 格式化数据, 私有方法
     * @param $params
     * @return array
     */
    private function _buildData(Array $params){
        $result = [];
        foreach($params as $item)
        {
            $id = $item[$this->primary_key];
            $parent_id = $item[$this->parent_key];
            $result[$parent_id][$id] = $item;
        }
        return $result;
    }







    /* 格式化数据, 私有方法 */
    private static function buildData($data,$options){
        $config = array_merge(self::$config,$options);
        self::$config = $config;
        extract($config);

        $r = array();
        foreach($data as $item)
        {
            $id = $item[$primary_key];
            $parent_id = $item[$parent_key];
            $r[$parent_id][$id] = $item;
        }

        return $r;
    }

    /* 生成树核心, 私有方法  */
    private static function makeTreeCore($index,$data,$type='linear')
    {
        extract(self::$config);
        foreach($data[$index] as $id=>$item)
        {
            if($type=='normal'){
                if(isset($data[$id]))
                {
                    $item[$expanded_key]= self::$config['expanded'];
                    $item[$children_key]= self::makeTreeCore($id,$data,$type);
                }
                else
                {
                    $item[$leaf_key]= true;
                }
                $r[] = $item;
            }else if($type=='linear'){
                $parent_id = $item[$parent_key];
                self::$level[$id] = $index==0?0:self::$level[$parent_id]+1;
                $item['level'] = self::$level[$id];
                self::$result[] = $item;
                if(isset($data[$id]))
                {
                    self::makeTreeCore($id,$data,$type);
                }
                $r = self::$result;
            }
        }
        return $r;
    }
}