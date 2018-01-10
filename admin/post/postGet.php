<?php 

    include '../../config.php';
    include '../../fn.php';
    //获取指定页面的数据

    $page=$_GET['page']; // 页码
    $pageNum=$_GET['pageNum']; // 一页数据总条数

    $status=$_GET['status']; //获取用户选择状态
    $cateid=$_GET['cateid']; //获取用户选中分类id
    //如果是所有状态 用% 匹配全部
    if($status=='all'){
        $status='%'; 
    }
    //如果是所有分类 用%匹配全部    
    if($cateid==0){
        $cateid='%';
    }

    //起始索引 （页码-1）*pageNum 
    $index=($page-1)*$pageNum;
    //联合查询 文字所有信息，作者昵称，分类名称  实现分页
    $sql="select posts.*, users.nickname,categories.name from posts 
    
    join users on posts.user_id=users.id
        
    join categories on posts.category_id =categories.id 

    where  posts.category_id like '$cateid'  and posts.status like '$status'  
    
    order  by posts.id desc
    
    limit $index,$pageNum";

    //执行sql
    $data=my_query($sql);
    //后台返回json数据
    echo json_encode($data);

?>