<?php 

    //删除session中存放的当前用户的id
    session_start();//开启session
    //删除当前用户的id
    unset($_SESSION['current_user_id']); 

    //跳转到登录页
    header('location:./login.php');

?>