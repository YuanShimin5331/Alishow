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
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="stylesheet" href="../assets/vendors/pagination/pagination.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <style>
    .page-box .pagination{
      float:right;
    }

    .page-box .pagination>*{
       padding:5px 10px;
       border-radius: 2px;
       border-color:#ccc;
    }
  </style>
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
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline">
          <select name="" class="form-control input-sm cate-select">
            <option value="">所有分类</option>
             
          </select>
          <select name="" class="form-control input-sm state-select">
            <option value="">所有状态</option>     
          </select>
          <!-- <button class="btn btn-default btn-sm">筛选</button> -->
        </form>
        <div class="page-box"></div>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td>随便一个名称</td>
            <td>小小</td>
            <td>潮科技</td>
            <td class="text-center">2016/10/07</td>
            <td class="text-center">已发布</td>
            <td class="text-center">
              <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

    <!-- 定义页面标识 -->
    <?php $page='posts' ?>
  <!-- 侧边栏 -->
  <?php  include './inc/aside.php' ?>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <script src="../assets/vendors/pagination/jquery.pagination.js"></script>
  <script>NProgress.done()</script>

  <script>

$(function(){

      //文章状态
      //草稿（drafted）/ 已发布（published）/ 回收站（trashed）
      var state={
        drafted:'草稿',
        published:'已发布',
        trashed:'回收站'
      }
    //渲染数据方法
    function render(page,pageNum){
      //获取页面中状态和分类的id
      var cateid=$('.cate-select').val()||0;
      var status=$('.state-select').val()||'all';

      $.ajax({
        type:'get',
        url:'./post/postGet.php',
        data:{
          page:page||1,
          pageNum:pageNum||10,
          cateid:cateid, //分类
          status:status //状态
        },
        dataType:'json',
        success:function(info){
           console.log(info);
           //组装数据和模板
            //更新数据
           $('tbody').html((template('tmp-table',{list:info,state:state})));
        }
      })
    }

   //第一屏
   render(); 

    //生成分页标签功能
  function pageTag(page){
       //获取页面中状态和分类的id
       var cateid=$('.cate-select').val()||0;
       var status=$('.state-select').val()||'all';
    $.ajax({
        type:'get',
        url:'./post/postTotal.php',
        data:{
          cateid:cateid,
          status:status
        },
        dataType:'json',
        success:function(info){
          console.log(info);
          //生成分页
          $('.page-box').pagination(info.total,{
            prev_text:'上一页',
            next_text:'下一页',
            current_page:page||0,//分页标签选中那一页
            num_display_entries:7,
            load_first_page:false , //页面初始化是不执行回调函数
            callback:function(index){ //点击分页后的回调函数
                render(index+1);//index从0开始  后台接受的页码从1开始计算
                window.currentPage=index+1; //记录当前页             
            }
          });
        }
      })
  }
  
  pageTag();//生成分页


    //动态生成分类选项
    $.ajax({
      type:'get',
      url:'./categroy/cateGet.php',
      dataType:'json',
      success:function(info){
         console.log(info);
        $('.cate-select').html(template('tmp-cate',{list:info}));
      }
    })

    //动态生成状态选项
    $('.state-select').html(template('tmp-state',{obj:state}));


    //但筛选状态改变后，页面重新渲染
    $('.cate-select,.state-select').on('change',function(){
        render(); //重新渲染
        pageTag();//重新生成分页标签 
        //只有当数据总量发送变化时，需要重新调用pageTag方法
    })

     
  })
  </script>
  <!-- 表格数据模板 -->
  <script type="text/template" id="tmp-table" >
      {{ each list $v }}
          <tr>
            <td class="text-center" data-id={{ $v.id }}><input type="checkbox"></td>
            <td>{{ $v.title}}</td>
            <td>{{ $v.nickname }}</td>
            <td>{{ $v.name }}</td>
            <td class="text-center">{{ $v.created }}</td>
            <td class="text-center">{{ state[$v.status] }}</td>
            <td class="text-center" data-id={{ $v.id }}>
              <a href="./post-add.php?id={{ $v.id }}" class="btn btn-default btn-xs">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
      {{ /each }}
  </script>

  <!-- 分类模板 -->
  <script type="text/template" id="tmp-cate">
      <option value="0">所有分类</option>
      {{ each  list $v}}
        <option value="{{ $v.id }}">{{ $v.name }}</option>
      {{ /each }}
  </script>

    <!-- 状态模板 -->
    <!-- for  in    obj是 对象的属性  -->
    <script type="text/template" id="tmp-state">
        <option value="all">所有状态</option>
        <% for(var k in obj ) { %>
          <option value="<%= k %>"><%= obj[k] %></option>
        <% }  %>
   </script>

  
</body>
</html>
