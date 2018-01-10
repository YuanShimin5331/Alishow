<?php 

    include '../../config.php';
    include '../../fn.php';

    $icons=[
        'fa fa-glass',
        'fa fa-phone',
        'fa fa-fire',
        'fa fa-gift'
    ];
    // var_dump($_POST);
    //获取前端传递的数据
   $text= $_POST['text'];
   $title= $_POST['title'];
   $href= $_POST['href'];
   $icon= $icons[array_rand($icons)];

   //先取出数据之前导航json


   //获取导航的数据

   $sql="select * from options";

   $data=my_query($sql)[8]['value'];
//    echo $data;
    //$data 是json字符串
    $data=json_decode($data);//php数组
    // var_dump($data); 
    //把我们添加的数据组合成要给一维数组
    $nav=[
        "icon"=>$icon,
        "text"=>$text,
        "title"=>$title,
        "link"=>$href
    ];

    $data[]=$nav; //把自己的数据添加到数组中
    // var_dump($data);

    //把添加了数据后数组在转成 json字符串 存储到数据库中
    $data=json_encode($data);
    // echo $data;

    //以更新数据的方式吧最新导航json数据添加到数据库
    $sql="update options set value='$data' where id=9";

    my_exec($sql);


  
?>