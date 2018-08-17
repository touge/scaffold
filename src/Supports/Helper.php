<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2018/8/17
 * Time: 下午9:39
 */

namespace Touge\Scaffold\Supports;

class Helper
{
    /**
     * 在数据适移文件中修改int类型字符串长度
     *
     * @param $table
     * @param array $columns
     * @return string
     */
    public static function change_int_length($table,Array $columns){
        $query = '';
        foreach($columns as $key=>$length){
            $query.= ",CHANGE `$key` `$key` INT($length) ";
        }
        $ALTER_SQL = "ALTER TABLE  `{$table}` ". substr($query,1);
        return $ALTER_SQL;
    }

    /**
     * 数据迁移文件中添加表注释
     *
     * @param $table
     * @param $comment
     * @return string
     */
    public static function change_table_comment($table,$comment){
        return "ALTER TABLE `$table` comment'{$comment}'";
    }
}