<?php
$title = 'User Categories';
$stylesheets = array('user-categories');
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1>User Categories</h1>
</div>
<table id="userlist">
  <tr>
    <th>#</th>
    <th>学号/用户名</th>
    <th>姓名</th>
    <th>性别</th>
    <th>学校</th>
    <th>专业</th>
    <th>年级</th>
    <th>手机</th>
    <th>Email</th>
    <th>QQ</th>
    <th>分组</th>
    <th>操作</th>
  </tr>
  <?php foreach ($this->profiles as $i => $profile): ?>
  <tr>
    <td><a id="<?php echo $profile->getUsername(); ?>" href="#<?php echo $profile->getUsername(); ?>"><?php echo $i + 1; ?></a></td>
    <td><?php echo $profile->getUsername(); ?></td>
    <td><?php echo $profile->getRealname(); ?></td>
    <td><?php echo $profile->getGender(); ?></td>
    <td><?php echo $profile->getSchool(); ?></td>
    <td><?php echo $profile->getMajor(); ?></td>
    <td><?php echo $profile->getGrade(); ?></td>
    <td><?php echo $profile->getPhoneNumber(); ?></td>
    <td><?php echo UserEmail::fetch($profile->getUsername()); ?></td>
    <td><?php echo $profile->getQq(); ?></td>
    <td><?php echo $profile->getClassName(); ?></td>
    <td>
      <?php foreach (array('A', 'B', 'C', 'D') as $category): ?>
        <?php if ($profile->getClassName() == $category): ?>
          <button class="btn" disabled><?php echo $category; ?></button>
        <?php else: ?>
          <form method="POST" action="<?php echo SITE_BASE; ?>/admin/user/categories">
            <input type="hidden" name="username" value="<?php echo $profile->getUsername(); ?>">
            <input type="hidden" name="class_name" value="<?php echo $category; ?>">
            <button class="btn" type="submit"><?php echo $category; ?></button>
          </form>
        <?php endif; ?>
      <?php endforeach; ?>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php
include(__DIR__ . '/../layout/footer.php');
