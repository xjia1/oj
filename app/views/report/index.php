<?php
$title = 'Reports';
include(__DIR__ . '/../layout/header.php');
?>
<h1>Reports</h1>
<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Visible</th>
      <th>Title</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>Duration</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->reports as $r): ?>
      <tr>
        <td><?php echo $r->getId(); ?></td>
        <td>
          <?php if ($r->getVisible()): ?>
            <i class="icon-ok"></i>
          <?php endif; ?>
        </td>
        <td><?php echo fHTML::prepare($r->getTitle()); ?></td>
        <td><?php echo $r->getStartDatetime(); ?></td>
        <td><?php echo $r->getEndDatetime(); ?></td>
        <td><?php echo $r->getStartDatetime()->getFuzzyDifference($r->getEndDatetime(), TRUE); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php
include(__DIR__ . '/../layout/footer.php');
