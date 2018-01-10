<?php 
    //返回所有分类数据
    include '../../config.php';
    include '../../fn.php';

    //获取id 根据id删除指定的数据
    $id=$_GET['id'];
    //删除sql
    $sql="delete from categories where id=$id";

    //执行
    my_exec($sql);

    //可以返回一个状态

?>