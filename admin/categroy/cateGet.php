<?php 
    //返回所有分类数据
    include '../../config.php';
    include '../../fn.php';

    //如果获取一条数据 根据id获取 
    //获取所有数据 不需要id 
    if(isset($_GET['id'])){
        $id=$_GET['id']; //3
    }else{
        $id='%'; 
    }

    $sql="select * from categories where id like '$id'";

    //执行查询
    $data=my_query($sql);
    // var_dump($data); 
    //返回json数据
    echo json_encode($data);

?>