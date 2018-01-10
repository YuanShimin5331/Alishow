<?php 

    include '../../config.php';
    include '../../fn.php';

    //获取导航的数据

    $sql="select * from options";

    $data=my_query($sql);

    // var_dump($data);

    echo json_encode($data);
?>