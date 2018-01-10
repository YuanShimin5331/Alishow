<?php 
    //1- 获取传递的索引，根据索引 删除数据
    $index=$_GET['index'];


    include '../../config.php';
    include '../../fn.php';

   
   //2-先取出数据之前导航json
   //获取导航的数据

   $sql="select * from options";

   $data=my_query($sql)[8]['value'];
//    echo $data;
    //$data 是json字符串
    //3-json转成 php数组
    $data=json_decode($data);
    // var_dump($data); 

    //4-删除对应索引的数据
    unset($data[$index]);
    // var_dump($data);

    //5-把添加了数据后数组在转成 json字符串 存储到数据库中
    $data=json_encode($data);
    // echo $data;

    //6-以更新数据的方式吧最新导航json数据添加到数据库
    $sql="update options set value='$data' where id=9";

    my_exec($sql);


?>