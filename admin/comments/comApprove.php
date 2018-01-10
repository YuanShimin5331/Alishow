<?php 

    include '../../config.php';
    include '../../fn.php';

    //根据前端传递的id  批准对应评论
    if(!empty($_POST['id'])){
        $id=$_POST['id'];

        //准备sql语句
        $sql="update comments set status='approved' where id in($id)";

        if(my_exec($sql)){
            $info=[
                "msg"=>"操作成功！",
                'status'=>200
            ];
        }else{
            $info=[
                "msg"=>"操作失败！",
                'status'=>100
            ];
        }

        echo json_encode($info);

    }

?>