<?php 
  //操作数据库的文件 在index1等主页面中已经引入，这个知识侧边栏的模板
  //展示用户信息
  // 1-获取当前用户id信息?
  // 用户登录后，会在session中保存 用户id,可以取出来使
  // session_start(); //一个页面session 不能重复开启
  $id=$_SESSION['current_user_id'];
  // 2-去数据库中取出当前用户的信息
  $sql="select * from users where id=$id";
  //查询
  $data=my_query($sql)[0];
  // var_dump($data);
  // 3-显示
    
  //是否是文章模块
  $isPosts=in_array($page,['posts','post-add','categories']);
  //是否是设置模块
  $isSet= in_array($page,['nav-menus','slides','settings']);

?> 

<!-- 侧边栏 -->
  <div class="aside">
    <div class="profile">
      <img class="avatar" src="<?php echo !empty($data['avatar'])?$data['avatar']:'../assets/img/default.png' ?>">
      <h3 class="name"><?php echo $data['nickname'] ?></h3>
      <?php echo $page ?>
    </div>
    <ul class="nav">
      <li <?php echo $page=='index'?'class="active"':'' ?> >
        <!-- 选中.html 连续按 ctrl+D 向下选中所有.html 修改为PHP   -->
        <a href="index1.php"><i class="fa fa-dashboard"></i>仪表盘</a>
      </li>
      <!-- 文章导航 -->
      <li <?php echo  $isPosts?'class="active"':'' ?> >
      <!-- 导航展开后，箭头向下（去掉 collapsed 类名） -->
        <a href="#menu-posts" class="<?php echo   $isPosts?'':'collapsed' ?>" data-toggle="collapse">
          <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
        </a>
        <!--  in 类名让ul展开 -->
        <ul id="menu-posts" class="collapse <?php echo $isPosts?'in':'' ?> ">
          <li <?php echo $page=='post-add'?'class="active"':'' ?>  ><a href="post-add.php">写文章</a></li>
          <li <?php echo $page=='posts'?'class="active"':'' ?>  ><a href="posts.php">所有文章</a></li>
          <li <?php echo $page=='categories'?'class="active"':'' ?>  ><a href="categories.php">分类目录</a></li>
        </ul>
      </li>
      <li <?php echo $page=='comments'?'class="active"':'' ?>>
        <a href="comments.php"><i class="fa fa-comments"></i>评论</a>
      </li>
      <li <?php echo $page=='users'?'class="active"':'' ?>>
        <a href="users.php"><i class="fa fa-users"></i>用户</a>
      </li>
      <!-- 网站设置 -->
      <li <?php  echo $isSet?'class="active"':'' ?> >
        <a href="#menu-settings" class="<?php echo $isSet?'':'collapsed'?>" data-toggle="collapse">
          <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-settings" class="collapse <?php echo $isSet?'in':'' ?>">
          <li <?php echo $page=='nav-menus'?'class="active"':'' ?> ><a href="nav-menus.php">导航菜单</a></li>
          <li <?php echo $page=='slides'?'class="active"':'' ?> ><a href="slides.php">图片轮播</a></li>
          <li <?php echo $page=='settings'?'class="active"':'' ?> ><a href="settings.php">网站设置</a></li>
        </ul>
      </li>
    </ul>
  </div>