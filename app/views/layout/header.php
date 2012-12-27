<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title><?php echo fHTML::encode($title . TITLE_SUFFIX); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php if (isset($meta_description)): ?>
    <meta name="description" content="<?php echo $meta_description; ?>">
  <?php endif; ?>
  <?php if (isset($meta_author)): ?>
    <meta name="author" content="<?php echo $meta_author; ?>">
  <?php endif; ?>
  <link href="<?php echo ASSET_CSS; ?>/css/bootstrap.min.css" rel="stylesheet">
  <style> body { padding-top: 60px; } </style>
  <link href="<?php echo ASSET_CSS; ?>/css/bootstrap-responsive.min.css" rel="stylesheet">
  <link href="<?php echo ASSET_CSS; ?>/css/prettify.css" media="screen" rel="stylesheet">
  <link href="<?php echo ASSET_CSS; ?>/css/footer.css" media="screen" rel="stylesheet">
  <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <?php if (isset($stylesheets)): ?>
    <?php foreach ($stylesheets as $stylesheet): ?>
      <link href="<?php echo ASSET_CSS; ?>/css/<?php echo $stylesheet; ?>.css" rel="stylesheet">
    <?php endforeach; ?>
  <?php endif; ?>
</head>
<body onload="prettyPrint()">
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="<?php echo SITE_BASE; ?>">Online Judge</a>
      <?php if (fAuthorization::checkLoggedIn()): ?>
        <div class="btn-group pull-right">
          <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="icon-user"></i> 当前用户：<?php echo fAuthorization::getUserToken(); ?>
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo SITE_BASE; ?>/email/verify">Email：<?php echo User::getVerifiedEmail(); ?></a></li>
            <li class="divider"></li>
            <li><a href="<?php echo SITE_BASE; ?>/change/info">修改个人信息</a></li>
            <li><a href="<?php echo SITE_BASE; ?>/change/password">修改密码</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo SITE_BASE; ?>/logout">登出</a></li>
          </ul>
        </div>
        <div class="navbar-text pull-right">
          <time>服务器时间：<?php echo new fTimestamp(); ?></time>
        </div>
      <?php else: ?>
        <form action="<?php echo SITE_BASE; ?>/login" method="POST" class="form-inline pull-right" style="margin: 0">
          <input type="text" class="input-small" placeholder="用户名" name="username" maxlength="80">
          <input type="password" class="input-small" placeholder="密码" name="password" maxlength="80">
          <input type="submit" class="btn btn-primary" name="action" value="登录"><?php
          ?><a class="btn btn-success" href="<?php echo SITE_BASE; ?>/login">注册</a>
        </form>
      <?php endif; ?>
      <div class="nav-collapse">
        <ul class="nav">
          <li class="nav-home"><a href="<?php echo SITE_BASE; ?>/">首页</a></li>
          <!--<li class="nav-sets"><a href="<?php echo SITE_BASE; ?>/sets">Problem Sets</a></li>-->
          <li class="nav-problems"><a href="<?php echo SITE_BASE; ?>/problems">题库</a></li>
          <li class="nav-reports"><a href="<?php echo SITE_BASE; ?>/contests">比赛</a></li>
          <li class="nav-homework"><a href="<?php echo SITE_BASE; ?>/homework">作业</a></li>
          <?php if (fAuthorization::checkLoggedIn()): ?>
            <li class="nav-submit"><a href="<?php echo SITE_BASE; ?>/submit">提交</a></li>
          <?php endif; ?>
          <li class="nav-status"><a href="<?php echo SITE_BASE; ?>/status">评测状态</a></li>
          <!--<li class="nav-ranklist"><a href="<?php echo SITE_BASE; ?>/ranklist">排名</a></li>-->
          <?php if (fAuthorization::checkLoggedIn()): ?>
            <?php if (User::can('manage-site')): ?>
              <li class="nav-dashboard dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">管理 <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li class="nav-header">Problems</li>
                  <li><a href="<?php echo SITE_BASE; ?>/dashboard#problems">Show/Hide/Refresh Problem</a></li>
                  <?php if (User::can('rejudge-record')): ?>
                    <li class="divider"></li>
                    <li class="nav-header">Records</li>
                    <li><a href="<?php echo SITE_BASE; ?>/dashboard#rejudge">Rejudge Record</a></li>
                    <li><a href="<?php echo SITE_BASE; ?>/dashboard#manjudge">Manually Judge Record</a></li>
                  <?php endif; ?>
                  <?php if (User::can('create-report') or User::can('remove-report') or User::can('view-any-report')): ?>
                    <li class="divider"></li>
                    <li class="nav-header">Reports</li>
                    <?php if (User::can('create-report')): ?>
                      <li><a href="<?php echo SITE_BASE; ?>/dashboard#create_report">Create Report</a></li>
                    <?php endif; ?>
                    <?php if (User::can('view-any-report') and User::can('remove-report')): ?>
                      <li><a href="<?php echo SITE_BASE; ?>/dashboard#reports">Show/Hide/Remove Report</a></li>
                    <?php elseif (User::can('view-any-report')): ?>
                      <li><a href="<?php echo SITE_BASE; ?>/dashboard#reports">Show/Hide Report</a></li>
                    <?php else: ?>
                      <li><a href="<?php echo SITE_BASE; ?>/dashboard#reports">Remove Report</a></li>
                    <?php endif; ?>
                  <?php endif; ?>
                  <?php if (User::can('add-permission') or User::can('remove-permission')): ?>
                    <li class="divider"></li>
                    <li class="nav-header">Permissions</li>
                    <?php if (User::can('add-permission') and User::can('remove-permission')): ?>
                      <li><a href="<?php echo SITE_BASE; ?>/dashboard#permissions">Add/Remove Permission</a></li>
                      <li><a href="<?php echo SITE_BASE; ?>/dashboard#assigned_permissions">View Assigned Permissions</a></li>
                    <?php elseif (User::can('add-permission')): ?>
                      <li><a href="<?php echo SITE_BASE; ?>/dashboard#permissions">Add Permission</a></li>
                    <?php else: ?>
                      <li><a href="<?php echo SITE_BASE; ?>/dashboard#permissions">Remove Permission</a></li>
                    <?php endif; ?>
                  <?php endif; ?>
                  <li class="divider"></li>
                  <li class="nav-header">Variables</li>
                  <li><a href="<?php echo SITE_BASE; ?>/dashboard#set_variable">Set Variable</a></li>
                  <li><a href="<?php echo SITE_BASE; ?>/dashboard#variables">View All Variables</a></li>
                </ul>
              </li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>
<div id="wrap">
  <div class="container">
    <?php if (fMessaging::check('warning')): ?>
      <div class="alert">
        <a class="close" data-dismiss="alert">&times;</a>
        <strong>Warning!</strong> <?php echo fMessaging::retrieve('warning'); ?>
      </div>
    <?php endif; ?>
    <?php if (fMessaging::check('error')): ?>
      <div class="alert alert-error">
        <a class="close" data-dismiss="alert">&times;</a>
        <strong>Oh snap!</strong> <?php echo fMessaging::retrieve('error'); ?>
      </div>
    <?php endif; ?>
    <?php if (fMessaging::check('success')): ?>
      <div class="alert alert-success">
        <a class="close" data-dismiss="alert">&times;</a>
        <strong>Well done!</strong> <?php echo fMessaging::retrieve('success'); ?>
      </div>
    <?php endif; ?>
    <?php if (fMessaging::check('info')): ?>
    <div class="alert alert-info">
      <a class="close" data-dismiss="alert">&times;</a>
      <strong>Heads up!</strong> <?php echo fMessaging::retrieve('info'); ?>
    </div>
    <?php endif; ?>
