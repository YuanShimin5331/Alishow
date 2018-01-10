<?php 
    include '../../config.php';
    include '../../fn.php';
    //返回评论的总条数
    $sql="select count(*) as total from comments
        join posts  on comments.post_id=posts.id 
    ";
        
    $data=my_query($sql)[0];
    // var_dump($data);
    echo json_encode($data);

?>