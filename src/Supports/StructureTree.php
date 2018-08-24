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
    protected $result                   = [];

    /**
     * 层次暂存
     * @var array
     */
    protected $levels                   = [];


    /**
     * @param array $params
     * @param array $options
     * @return array
     */
    public function make(Array $params,$options=array()){
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
     * @param array $options
     * @return array
     */
    public function html_list(Array $params,$options=[]){
        $options = $this->buildData($params,$options);

        $options = $this->core(0,$options,'linear');
        $_level = $this->level;

        if($_level == 0) return $options;

        $_result_array = [];
        foreach($options as $key=>$val){
            if($val['level'] <= $_level)
            {
                array_push($_result_array,$val);
            }
        }
        return $_result_array;
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
                if(isset($params[$id])) {
                    $item[$this->expanded_key]= $this->expanded;
                    $item[$this->children_key]= $this->core($id,$params,$type);
                } else {
                    $item[$this->leaf_key]= true;
                }
                $result[] = $item;
            }else if($type=='linear'){
                $parent_id = $item[$this->parent_key];
                $this->levels[$id] = $index==0?0:$this->levels[$parent_id]+1;
                $item['level'] = $this->levels[$id];
                $this->result[] = $item;
                if(isset($data[$id])) {
                    $this->core($id,$params,$type);
                }
                $result = $this->result;
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