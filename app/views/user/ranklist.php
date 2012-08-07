<?php
$title = 'Authors Ranklist';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1>Authors Ranklist</h1>
</div>
<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>User</th>
      <th>Solved</th>
      <th>Tried</th>
      <th>Submissions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->user_stats as $i => $user_stat): ?>
      <tr>
        <td><?php echo $i + 1; ?></td>
        <td><?php echo $user_stat->getUsername(); ?></td>
        <td><?php echo $user_stat->getSolved(); ?></td>
        <td><?php echo $user_stat->getTried(); ?></td>
        <td><?php echo $user_stat->getSubmissions(); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php
include(__DIR__ . '/../layout/footer.php');
