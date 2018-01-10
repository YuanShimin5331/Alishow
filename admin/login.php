

<?php 

   //进入配置文件 和fn 文件
   include '../config.php';
    include '../fn.php';

   //如果通过连接打开 是get方式，只是显示页面给用户
   //如果提交表单 是post方式 ，验证用户名密码是否正确   
  //  var_dump($_SERVER);

 

   if($_SERVER['REQUEST_METHOD']=="POST"){
      //获取用户名和密码
      $email=$_POST['email']; 
      $password=$_POST['password'];

      //判断数据是否完整
      if(empty($_POST['email'])|| empty($_POST['password'])){
          $msg='数据填写不完整！';          
      }else{
          //当数据完整时进行验证
          //需要判断用户名和密码是否正确
          //根据用户名去数据中查找对应密码
          $sql="select * from users where email ='$email'";
    
          $data=my_query($sql); //$data是二维数组
    
          if($password==$data[0]['password']){        
            //在服务器中保存当前用户信息 
            session_start();//开始session
            $_SESSION['current_user_id']=$data[0]['id'];
              //成功 跳转到首页
              header('location:./index1.php');
              die();//页面跳转了，后面没有必要执行
          }else{
            $msg='用户名或者密码错误！';   
            //当前页就是登录页，不用跳转    
          }     
      }     
      
     
   }   
?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap" action="" method="post">
      <img class="avatar" src="../assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if(isset($msg)){ ?>
          <div class="alert alert-danger">
            <strong>错误！</strong> <?php echo $msg ?>
          </div>
      <?php }  ?>
    

      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" 
        type="email" 
        class="form-control" 
        placeholder="邮箱" 
        autofocus
        name="email"    
        value="<?php echo isset($msg)?$email:"" ?>";
        

        >
      </div>
      
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" 
        type="password" 
        class="form-control" 
        placeholder="密码"
        name="password"
        >
      </div>    
      <input class="btn btn-primary btn-block" type="submit" value="登录">
    </form>
  </div>
</body>
</html>
