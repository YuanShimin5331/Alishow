<?php 
    include_once '../../config.php';
    include_once '../../fn.php';
    //获取所有评论数据 返回给前端   json 

    // 1  一页10条
    // 第一页  0  取10条      起始索引： (page-1)*pageNum
    // 第二页  10  取10条   
    // 第三页   20  取10条 
    // 获取那一页的数据
    $page=$_GET['page'];
    // 一页多少条
    $pageNum=$_GET['pageNum'];

    $index=($page-1)*$pageNum; //获取的起始索引


    //准备sql 联合查询
    $sql="select comments.* ,posts.title  from comments 
          join posts  on comments.post_id=posts.id  limit $index,$pageNum";
    //执行查询
    $data= my_query($sql);
    // var_dump($data);
    // echo 'ok';

    // var_dump($data);
    //返回json格式的数据
    echo json_encode($data);

    // echo '.';

?>