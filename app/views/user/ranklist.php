<?php
$title = '用户排名';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1><?php echo $title; ?></h1>
</div>
<?php include(__DIR__ . '/../layout/_pagination.php'); ?>
<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>用户</th>
      <th>已解决的题目</th>
      <th>尝试过的题目</th>
      <th>总提交次数</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->user_stats as $i => $user_stat): ?>
      <tr>
        <td><?php echo $i + 1 + ($this->page - 1) * $this->user_stats->getLimit(); ?></td>
        <td><?php echo $user_stat->getUsername(); ?> <?php echo Profile::fetchRealName($user_stat->getUsername()); ?></td>
        <td><?php echo $user_stat->getSolved(); ?></td>
        <td><?php echo $user_stat->getTried(); ?></td>
        <td><?php echo $user_stat->getSubmissions(); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php
include(__DIR__ . '/../layout/_pagination.php');
include(__DIR__ . '/../layout/footer.php');
