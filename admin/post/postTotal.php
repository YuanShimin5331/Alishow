<?php 

include '../../config.php';
include '../../fn.php';


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

//获取文章是总数量
$sql="select count(*) as total from posts 

   where  category_id like '$cateid' and  status like '$status'";

$data=my_query($sql)[0];

echo json_encode($data);//返回json格式的数据  {"total":1001}

?>