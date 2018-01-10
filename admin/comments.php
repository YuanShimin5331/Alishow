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
  <title>Comments &laquo; Admin</title>
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
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <button class="btn btn-info btn-sm btn-approveds">批量批准</button>
          <!-- <button class="btn btn-warning btn-sm">批量拒绝</button> -->
          <button class="btn btn-danger btn-sm btn-dels">批量删除</button>
        </div>
        <!-- 分页的容器 -->
        <div class="page-box"></div>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input class="th-chk" type="checkbox"></th>
            <th>作者</th>
            <th>评论</th>
            <th>评论在</th>
            <th>提交于</th>
            <th>状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr class="danger">
            <td class="text-center"><input type="checkbox"></td>
            <td>大大</td>
            <td>楼主好人，顶一个</td>
            <td>《Hello world》</td>
            <td>2016/10/07</td>
            <td>未批准</td>
            <td class="text-center">
              <a href="post-add.html" class="btn btn-info btn-xs">批准</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
          <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td>大大</td>
            <td>楼主好人，顶一个</td>
            <td>《Hello world》</td>
            <td>2016/10/07</td>
            <td>已批准</td>
            <td class="text-center">             
              <a href="post-add.html" class="btn btn-warning btn-xs">驳回</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>        
        </tbody>
      </table>
    </div>
  </div>

    <!-- 定义页面标识 -->
    <?php $page='comments' ?>    <!-- 定义页面标识 -->
    <?php $page='comments' ?>
  <!-- 侧边栏 -->
  <?php  include './inc/aside.php' ?>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <script src="../assets/vendors/pagination/jquery.pagination.js"></script>

  <script>
    //状态对象
    //待审核（held）/ 准许（approved）/ 拒绝（rejected）/ 回收站（trashed）
    var state={
      held:'待审核',
      approved:'准许',
      rejected:'拒绝',
      trashed:'回收站'
    }

    //动态加载第一屏数据
    function render(page,pageNum){
      $.ajax({
          type:'get',
          url:'./comments/comGet.php',
          data:{
            page:page||1,
            pageNum:pageNum||10
          },
          dataType:'json', //未获取到数据！false  100  []  
          success:function(info){
            console.log(info); //数组
            //渲染
            $('tbody').html(template('tmp-table',{list:info ,state:state}));

          },
          error:function(){
             console.log('应该是json转换败了！');
          }
        })
    }
    render();



    //删除一条数据 事件委托
    // 1-点击删除按钮 获取该数据id
    // 2-将id传递给后台，有后台执行删除操作
    // 3-操作完成后，刷新页面数据，看到删除后效果
    $('tbody').on('click','.btn-del',function(){
       var id=$(this).parent().attr('data-id');
      //  alert(id);
        $.ajax({
          type:'post',
          url:'./comments/comDel.php',
          data:{id:id},
          dataType:'json',
          success:function(info){
            console.log(info);
            //如果删除成功
            if(info.status==200){
              //重新渲染表格
              render(window.currentPage);
            }
          }
        })
    });

    //批准评论
    //事件委托
    $('tbody').on('click','.btn-approved',function(){       
        //1-获取此数据id
        var id=$(this).parent().attr('data-id');
        //根据接口文档来批准数据
        $.ajax({
          type:'post',
          url:'./comments/comApprove.php',
          data:{id:id},
          dataType:"json",
          success:function(info){
            //重新渲染页面，看到批准结果
            render(window.currentPage);//渲染当前页
          }
        });
    })


    //批量选中逻辑
    //    1-准备一个容器 存放 选中的id 数组 
    //    2-当某个复选框被选中时，向容器中添加id （避免重复添加）
    //    3-当某个复选框被取消时，从容器中删除对应的id 
    //    4-如果有复选框被选中，批量按钮显示，否则隐藏
    //    5-点击按钮 进行批量 操作
     var ids=[]; //存放id的容器
    $('tbody').on('change','.td-chk',function(){
        //获取对应的id
        var id=$(this).parent().attr('data-id');
       
        var index=ids.indexOf(id); //如果index==-1  说明数组中没有这个id       

        //如果该复选框被选中，向容器中添加id
        // 要防止重复添加
        //如果该复选框被取消删除此id
        if($(this).prop('checked')){
          //添加
          if(index==-1){ //如果数组中没有这id则添加
             ids.push(id);
          }        
        }else{
          //删除
          ids.splice(index,1); //删除该id 参数1：起始索引，参数：删几个
        }

        //2-如果有复选框被选中，批量按钮显示，否则隐藏
        ids.length>0?$('.btn-batch').show():$('.btn-batch').hide();       

        var strid=ids.join();//把数组中id拼接成字符串
        console.log(strid);

        //缓存批量选中的id
        $('.btn-batch').attr('data-id',strid);     

        //如果所有复选框都选中 ，则选中表头全选复选框，否则不选中
      if(ids.length==$('.td-chk').length){
        $('.th-chk').prop('checked',true);
      }else{
        $('.th-chk').prop('checked',false);
      }          

    })


    //全选的逻辑
    $('.th-chk').change(function(){
       //点击全选按钮，把全选按钮的状态 赋值给 所有td中的复选框      
       $('.td-chk').prop('checked', $(this).prop('checked')).trigger('change');
    })


    //批量批准
    $('.btn-approveds').click(function(){
       //获取id 
       var id=$(this).parent().attr('data-id');
       //通过ajax请求后台
       $.ajax({
         type:'post',
         url:'./comments/comApprove.php',
         data:{id:id},
         dataType:'json',
         success:function(info){
            console.log(info);
            //重新渲染表格
            render(window.currentPage);
            //清空ids容器，下一次重新开计数
            ids=[];
            $('.th-chk').prop('checked',false);//取消全选复选框
            $('.btn-batch').hide();//隐藏批量操作按钮
         }
       })
    })

    //批量删除
    $('.btn-dels').click(function(){
      //获取要删除数据的id
      var id=$(this).parent().attr('data-id');
      //删除
      $.ajax({
        type:'post',
        url:'./comments/comDel.php',
        data:{id:id},
        dataType:'json',
        success:function(info){
          console.log(info);
            render(window.currentPage);//重新渲染表格数据
            ids=[];//清空ids容器
            $('.th-chk').prop('checked',false);//取消全选复选框
            $('.btn-batch').hide();//隐藏批量操作按钮            
        }
      });

    })

    //生成分页
    // 1-实现接口，查询数据库中数据的总条数
    // 2-ajax请求后台服务器，获取总条数
    // 3-根据数据总条数生成分页
    $.ajax({
      type:'get',
      url:'./comments/comTotal.php',
      dataType:'json',
      success:function(info){
        console.log(info); //{total:506}
        //生成分页
        $('.page-box').pagination(info.total,{
          prev_text:'上一页',
          next_text:'下一页',
          num_display_entries:7, //分页标签数量
          load_first_page:false,
          callback:function(index){
             render(index+1); //点击获取对应页面的数据
             window.currentPage=index+1;//记录当前页码
             //跳转到新页面 进行重置 
             ids=[];//清空ids容器
            $('.th-chk').prop('checked',false);//取消全选复选框
            $('.btn-batch').hide();//隐藏批量操作按钮 
          }
        });
      }
    })







  </script>
  <script>NProgress.done()</script>

  <!-- 表格数据模板 -->
  <script type="text/template" id="tmp-table" >
      {{ each list $v}}
        <tr>
            <td class="text-center" data-id="abc"+{{ $v.id}}><input class="td-chk" type="checkbox"></td>
            <td>{{ $v.author }}</td>
            <td>{{ $v.content.substr(0,20)+'...' }}</td>
            <td>《{{ $v.title }}》</td>
            <td>{{ $v.created}}</td>
            <td>{{ state[$v.status] }}</td>
            <td class="text-right" data-id={{ $v.id }}>
              <!-- 待审核数据需要批准 -->
              {{ if $v.status=='held' }}
                <a href="javascript:;" class="btn btn-info btn-xs btn-approved">批准</a>
              {{ /if }}

              <a href="javascript:;" class="btn btn-danger btn-xs btn-del">删除</a>
            </td>
          </tr>
      {{ /each  }}
  </script>
</body>
</html>

