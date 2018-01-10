<?php 
    //引入配置
    // include_once '../config.php';

    //封装数据操作的工具函数
    //1-数据库的非查询语句
    // 返回值 操作成功 返回true  失败返回 false  
    function my_exec($sql){
        //连接数据库
        //成功返回数据库连接  失败返回false
        @$link=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
        //错误处理
        if(!$link){
            // echo '数据库连接失败!';
            return false;
        }

        //执行sql语句
        if(!mysqli_query($link,$sql)){
            // echo '操作失败:'.mysqli_error($link);
            //关闭数据库连接
            mysqli_close($link);
            return false;
        }
        //关闭数据库连接
        mysqli_close($link);
        return true;
    }

        //  $sql="delete from posts where id=20";
        //  var_dump(my_exec($sql));

    //数据库的查询语句
    //成功返回二维数组的数据 失败返回 false 
    function my_query($sql){
    //连接数据库
    //成功返回数据库连接  失败返回false
     @$link=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
     //错误处理
      if(!$link){
        //   echo '数据库连接失败!';
          return false;
      }
      //执行sql
      //成功 返回结果集  失败false
      $res=mysqli_query($link,$sql);
      //如果结果为false ，结果集的行数为0 直接结束
      if(!$res || mysqli_num_rows($res)==0){
        //   echo '未获取到数据！';
          //关闭连接
          mysqli_close($link);
          return false;
      }

      //获取结果
      //$row  是关联数组  $arr 是二维数组
       while($row=mysqli_fetch_assoc($res)){
           $arr[]=$row; 
       }
        //关闭连接
        mysqli_close($link);

       return  $arr; //返回二维数组形式的数据
    }


    //判断用户是否登录
    function checkLogin(){
        //1-判断浏览器是否携带 PHPSESSID 过来
        //2-服务器是否记录有用户信息
        if(isset($_COOKIE['PHPSESSID'])){
            //判断session数据
            session_start();//开启session 
            if(isset($_SESSION['current_user_id'])){
            //登录成功
            }else{
            // 没有 登录
            //调回登录页
            header('location:./login.php');
            die();
            }
        }else{
            //调回登录页
            header('location:./login.php');
            die();
        }
    }
 

?>