<?php 
  include '../config.php';
  include '../fn.php';
  //判断登录
  checkLogin();

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <nav class="navbar">
      <button class="btn btn-default navbar-btn fa fa-bars"></button>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="profile.html"><i class="fa fa-user"></i>个人中心</a></li>
        <li><a href="login.html"><i class="fa fa-sign-out"></i>退出</a></li>
      </ul>
    </nav>
    <div class="container-fluid">
      <div class="page-title">
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger error-box" style="display:none">
        <strong>错误！</strong><span>发生XXX错误</span>
      </div>
      <div class="row">
        <div class="col-md-4">
          <!-- 添加分类的表单 -->
          <form id="add-form" >
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong class="slug-strong">slug</strong></p>
            </div>
            <div class="form-group">             
              <input  class="btn btn-primary btn-add"  type="button" value="添加">
              <input  class="btn btn-primary btn-update" style="display:none"  type="button" value="修改">
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <!-- 显示动态渲染的数据 -->
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td>未分类</td>
                <td>uncategorized</td>
                <td class="text-center">
                  <a href="javascript:;" class="btn btn-info btn-xs">编辑</a>
                  <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>             
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- 定义页面标识 -->
  <?php $page='categories' ?>
  <!-- 侧边栏 -->
  <?php  include './inc/aside.php' ?>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <script>NProgress.done()</script>

  <script>
    $(function(){
        //渲染数据的方法
        function render(){
            $.ajax({
              type:'get',
              url:'./categroy/cateGet.php',
              dataType:'json',
              success:function(info){
                console.log(info); //数组
                $('tbody').html(template('tmp-table',{list:info}));
              }
            })
        }

        render();

        //添加操作
        $('.btn-add').click(function(){
          //获取表单数据
          var data=$('#add-form').serialize();//表单序列化 name=zs&slug=20
          //添加数据
          $.ajax({
            type:'get',
            url:'./categroy/cateAdd.php',
            data:data,
            dataType:'json',
            success:function(info){
              console.log(info);
              // info.code  info.msg
              if(info.code==200){
                //成功
                // 清空表单
                //DOM中的 表单重置的方法 reset()
                $('#add-form')[0].reset();
                $('.error-box').hide();//隐藏消息盒子
              }else{
                //失败
                //显示失败消息
                $('.error-box').show().children('span').text(info.msg);
              }

              render();//重新渲染
            }
          });
        })

        //别名同步
        $('#slug').on('input',function(){
           $('.slug-strong').text($(this).val());
        })

        //删除分类
        $('tbody').on('click','.btn-del',function(){
           //获取id 
           var  id=$(this).parent().attr('data-id');
           //删除
           $.ajax({
             type:'get',
             url:'./categroy/cateDel.php',
             data:{id:id},
             success:function(){
               //重新渲染
               render();
             }
           })
        })

        //编辑分类01-获取分类信息并显示
        $('tbody').on('click','.btn-edit',function(){
           var id=$(this).parent().attr('data-id');//获取id
           //获取对应id的信息
           $.ajax({
              type:'get',
              url:'./categroy/cateGet.php',
              data:{id:id},
              dataType:'json',
              success:function(info){

                console.log(info[0]);
                info=info[0];//对象
                //数据填充              
                $('#name').val(info.name);
                //数据填充 并 触发slug的input事件
                $('#slug').val(info.slug).trigger('input');
                $('#add-form').attr('data-id',info.id);//缓存id

                //添加按钮隐藏，修改的按钮显示
                $('.btn-add').hide();
                $('.btn-update').show();

              }
           });
        })

        //点击修改按钮把数据更新到数据库中
        $('.btn-update').click(function(){
           //获取id
           var id=$('#add-form').attr('data-id');
           //获取表单数据
           var data=$('#add-form').serialize(); //name=aa&&slug=bb
           //拼接id
           var data=data+'&id='+id;
           //发送ajax 进行更新操作
           $.ajax({
             type:'get',
             url:'./categroy/cateAdd.php',
             data:data,
             dataType:'json',
             success:function(info){
                render(); //重新渲染数据
                // 表单重置
                $('#add-form')[0].reset();
                $('.slug-strong').text('slug'); //文本复位
                $('.btn-update').hide();
                $('.btn-add').show(); 


             }
           })
         
        })



    });
  </script>

  <script type="text/template" id="tmp-table">
    {{ each list $v }}
       <tr >
          <td class="text-center" data-id={{ $v.id }}><input type="checkbox"></td>
          <td>{{ $v.name }}</td>
          <td>{{ $v.slug }}</td>
          <td class="text-center"  data-id={{ $v.id }} >
            <a href="javascript:;" class="btn btn-info btn-xs btn-edit">编辑</a>
            <a href="javascript:;" class="btn btn-danger btn-xs btn-del">删除</a>
          </td>
        </tr>  
    {{ /each }}
 </script>
</body>
</html>
