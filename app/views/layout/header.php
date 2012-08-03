<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo $title . TITLE_SUFFIX; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="<?php echo SITE_BASE; ?>/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
    }
  </style>
  <link href="<?php echo SITE_BASE; ?>/css/bootstrap-responsive.min.css" rel="stylesheet">
  <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>
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
            <i class="icon-user"></i> <?php echo fAuthorization::getUserToken(); ?>
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li class="divider"></li>
            <li><a href="<?php echo SITE_BASE; ?>/change/password">Change Password</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo SITE_BASE; ?>/logout">Sign Out</a></li>
          </ul>
        </div>
      <?php else: ?>
        <form action="<?php echo SITE_BASE; ?>/login" method="POST" class="form-inline pull-right" style="margin: 0">
          <input type="text" class="input-small" placeholder="Username" name="username" maxlength="80">
          <input type="password" class="input-small" placeholder="Password" name="password" maxlength="80">
          <button type="submit" class="btn">Sign In</button>
        </form>
      <?php endif; ?>
      <div class="nav-collapse">
        <ul class="nav">
          <li class="nav-home"><a href="<?php echo SITE_BASE; ?>/home">Home</a></li>
          <li class="nav-sets"><a href="<?php echo SITE_BASE; ?>/sets">Problem Sets</a></li>
          <li class="nav-problems"><a href="<?php echo SITE_BASE; ?>/problems">All Problems</a></li>
          <?php if (fAuthorization::checkLoggedIn()): ?>
            <li class="nav-submit"><a href="<?php echo SITE_BASE; ?>/submit">Submit</a></li>
          <?php endif; ?>
          <li class="nav-status"><a href="<?php echo SITE_BASE; ?>/status">Status</a></li>
          <?php if (fAuthorization::checkLoggedIn()): ?>
            <li class="nav-reports"><a href="<?php echo SITE_BASE; ?>/reports">Reports</a></li>
            <?php if (User::can('manage-site')): ?>
              <li class="nav-dashboard"><a href="<?php echo SITE_BASE; ?>/dashboard">Dashboard</a></li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>
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