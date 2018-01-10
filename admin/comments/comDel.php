<?php 

    include '../../config.php';
    include '../../fn.php';
    //根据id 删除评论数据
    if(!empty($_POST['id'])){
        //获取前端传递id
        $id=$_POST['id'];
        //根据id 删除数据
        $sql="delete from comments where id in ($id)";

        if(my_exec($sql)){
            $info=[
                "msg"=>"操作成功！",
                "status"=>200
            ];
        }else{
            $info=[
                "msg"=>"操作失败！",
                "status"=>100
            ];
        }

        echo json_encode($info);

        //DBA 不会真的删除数据  软删除  逻辑删除  备份  异地容灾
        // zs  123  20 男  ...       isDel true  update set isDel=true;



    }
  

?>