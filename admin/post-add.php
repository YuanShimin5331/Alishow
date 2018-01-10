<?php 
   include '../config.php';
  include '../fn.php';
  //判断登录
  checkLogin();

  // 添加文章，不需要id,id在数据库中是自增的 ，只需插入新内容即可
  // 更新文章：需要获取次文章的id,根据id进行修改
  // 在posts-add.php文件中添加隐藏域来保存当前文章的id 
  //实现修改的功能
  //判断是否是修改
  $isUpate=isset($_GET['id']);

  if($isUpate){ //如果是更新
     $id=$_GET['id']; //获取文字id
     //根据id获取此文字的信息
     $sql="select * from posts where id=$id";
    //查询数据
     $data=my_query($sql)[0];
    //往页面中填充数据

    // var_dump($data);


  }

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
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
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="row" action="./post/postAdd.php" method="post" enctype="multipart/form-data">
      <!-- 如果是更新 在隐藏域中存放当前修改数据的id  -->
        <input type="hidden" name="postid" value="<?php echo $isUpate?$id:'' ?>" >
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" 
            class="form-control input-lg" 
            name="title" type="text" 
            placeholder="文章标题"
            value="<?php echo  $isUpate?$data['title']:'' ?>"
            >
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <textarea id="content" 
            class="form-control input-lg" 
            style="display:none" name="content" 
            cols="30" rows="10" 
            placeholder="内容">
            <?php echo $isUpate?$data['content']:''?>
          </textarea>
            <div id="content-box"></div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" 
            class="form-control" 
            name="slug" type="text" 
            placeholder="slug"
            value="<?php echo  $isUpate?$data['slug']:'' ?>"
            >
            <p class="help-block">https://zce.me/post/
              <strong id="slug-strong"><?php echo  $isUpate?$data['slug']:'slug' ?></strong>
            </p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail feature-img" 
             style="display: none;height:80px;width:auto;"
             <?php echo  $isUpate&&$data['feature']? 'src=../'.$data['feature']:'' ?>
            >
            <input id="feature" class="form-control" name="feature" type="file" >
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" 
            class="form-control" 
            name="category" 
            data-id="<?php echo $isUpate?$data['category_id']:'' ?>">
          
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" 
            class="form-control" 
            name="created" 
            type="datetime-local" 
            data-time="<?php echo $isUpate?$data['created']:'' ?>">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status" data-state="<?php echo $isUpate?$data['status']:'' ?>">
              
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- 定义页面标识 -->
  <?php $page='post-add' ?>

  <!-- 侧边栏 -->
  <?php  include './inc/aside.php' ?>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <script src="../assets/vendors/moment/moment.js"></script>
  <script src="../assets/vendors/wangEditor/wangEditor.min.js"></script>
  <script>NProgress.done()</script>

  <script>
    //获取数据 渲染 分类列表 和状态列
    $(function(){
      //文章状态
      //草稿（drafted）/ 已发布（published）/ 回收站（trashed）
      var state={
        drafted:'草稿',
        published:'已发布',
        trashed:'回收站'
      }
      //1-生成状态
     $('#status').html(template('tmp-state',{obj:state}));
     //设置默认状态选中项
      var dataState= $('#status').attr('data-state');
      console.log(dataState);
      // $('#status option[value="'+dataState+'"]').prop('selected',true);
      $('#status option[value="'+dataState+'"]').prop('selected',true);

      //2-生成文章分类
      $.ajax({
        type:'get',
        url:'./categroy/cateGet.php',
        dataType:'json',
        success:function(info){
          console.log(info);
          //生成分类之后 设置默认选中项
          $('#category').html(template('tmp-cate',{list:info}));
          var id= $('#category').attr('data-id');
          $('#category option[value='+id+']').prop('selected',true);
        }
      })


      //3-动态填充当前时间
       // Fri Dec 15 2017 11:34:32 GMT+0800 (中国标准时间) jsdate 获取的时间
      // var  date=new Date();
      // var t1=date.toString();
      // t1=moment(t1).format('YYYY-MM-DD hh:mm:ss');
      //"2017-12-17T02:57"  datetime-local 标签支持的时间格式
      // t1=moment(t1).format('YYYY-MM-DDThh:mm');


      //如果盒子上缓存的有服务器时间则用服务器时间
      //如果没有用当前时间
      var t1=$('#created').attr('data-time');

      if(t1){
         t1=moment(t1).format('YYYY-MM-DDThh:mm');//取服务器的时间
      }else{
         t1=moment().format('YYYY-MM-DDThh:mm'); //取当前时间
      }     
      console.log(t1);     
      $('#created').val(t1);

      //4-别名同步显示
      //h5新增的表单输入事件，大多用于移动端统计字数使用
      $('#slug').on('input',function(){
        $('#slug-strong').text($(this).val()); //把表单中输入的值 填充给strong标签
      });

      //5-显示富文本编辑器
        var E = window.wangEditor
        var editor = new E('#content-box');    
        //把富文本编辑器中内容及时的同步到textarea   
        editor.customConfig.onchange = function (html) {
            // 监控变化，同步更新到 textarea
           $('#content').val(html);
        }
        editor.create();   

        //将textarea中内容添加给富文本编辑器
        editor.txt.html($('#content').val()); 

        //6-图片本地预览
        $('#feature').on('change',function(){
           //获取选中文件
           var file=this.files[0];//选中第一个上传文件
           //转成url
           var url=URL.createObjectURL(file);
           $('.feature-img').attr('src',url).show();
        })

        //7- 如果特色图片 中src有数据，则显示
        $('.feature-img').attr('src')? $('.feature-img').show():'';



    })
  </script>

  <!-- 分类模板 -->
  <script type="text/template" id="tmp-cate">    
      {{ each  list $v}}
        <option value="{{ $v.id }}">{{ $v.name }}</option>
      {{ /each }}
  </script>

    <!-- 状态模板 -->
    <!-- for  in    obj是 对象的属性  -->
    <script type="text/template" id="tmp-state">     
        {{ each obj $v $k }}
          <option value="{{ $k }}">{{ $v }}</option>
        {{ /each }}
   </script>


</body>
</html>
