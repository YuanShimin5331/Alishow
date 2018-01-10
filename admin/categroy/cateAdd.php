<?php 

    include_once '../../config.php';
    include_once '../../fn.php';

    //添加分类
    //数据不完整 不能添加
    $name=$_GET['name'];
    $slug=$_GET['slug'];

    //判断数据是否为空
    if(empty($name)||empty($slug)){
        $info=[
            "msg"=>"数据不完整！",
            "code"=>100
        ];
    }else{

        //判断更新还是添加
        //更新是根据id进行更新
        //添加不需要id,id自增
        $isUpdate=!empty($_GET['id']);

        if($isUpdate){
            //更新
            $id=$_GET['id'];
            $sql="update categories set name='$name',slug='$slug' where id =$id";
        }else{
            //添加
            //准备sql
            $sql="insert into categories (name,slug) values('$name','$slug')";
        }

        //执行
       if(my_exec($sql)){
            $info=[
                "msg"=>"添加成功！",
                "code"=>200
            ];
       }else{
            $info=[
                "msg"=>"添加失败！",
                "code"=>100
            ];
       }
    }

    echo json_encode($info);//返回json 
?>