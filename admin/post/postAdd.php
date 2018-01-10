<?php 

    include '../../config.php';
    include '../../fn.php';
  // 设计思路  
  // 添加文章，不需要id,id在数据库中是自增的 ，只需插入新内容即可
  // 更新文章：需要获取次文章的id,根据id进行修改
  // 在posts-add.php文件中添加隐藏域来保存当前文章的id 

    //用于处理添加文章逻辑
    // var_dump($_POST);
    // var_dump($_FILES);
    //1和2的步骤是添加和修改公用的代码
    //1-获取文章数据信息
    $title=$_POST['title'];
    $content=$_POST['content'];
    $slug=$_POST['slug'];
    $cateid=$_POST['category'];
    $created=$_POST['created'];
    $status=$_POST['status'];
    //先开启在使用
    session_start();
    $userid=$_SESSION['current_user_id'];//用户id

    //2-保存图片
    if(!empty($_FILES['feature'])&&$_FILES['feature']['error']==0){
        //转移图片进行保存
        //获取临时文件
        $ftmp=$_FILES['feature']['tmp_name'];
        //拿到文件名，截取后缀名
        $fname=$_FILES['feature']['name'];
        $ext=strrchr($fname,'.');
        $newName='uploads/'.time().rand(1000,9999).$ext;
        //转移文件
        if(move_uploaded_file($ftmp,'../../'.$newName)){
            //在数据中存储文件绝对路径
            $feature=$newName; //文件转移成功，保存文件存储路径
        }

    }

     //3-保存文章数据信息，在数据库中保存图片在服务器中存储地址
     //4-有图片 和没图片sql 是不同的

    //3-判断是否是修改
    $isUpdate=!empty($_POST['postid']);

    if($isUpdate){
        //获取id
        $id=$_POST['postid'];
        //修改数据
        if(!empty($feature)){
            //有图片
            $sql="update posts set slug='$slug',title='$title',created='$created',content='$content',
            status='$status',category_id='$cateid',feature='$feature' where id=$id";
        }else{
            //没图片
            $sql="update posts set slug='$slug',title='$title',created='$created',content='$content',
            status='$status',category_id='$cateid' where id=$id";
        }
        }else{
        //添加数据
        if(!empty($feature)){ //如果有上传图片且成功
            $sql="insert into posts (slug,title,created,content,status,user_id,category_id,feature) 
               values('$slug','$title','$created','$content','$status','$userid','$cateid','$feature')";
       }else{ //没有上传图片
           $sql="insert into posts (slug,title,created,content,status,user_id,category_id) 
           values('$slug','$title','$created','$content','$status','$userid','$cateid')";
       }
    }
    // echo $sql;    

    my_exec($sql);

    header('location:../posts.php');

?>