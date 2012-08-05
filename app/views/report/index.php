<?php
$title = 'Reports';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1>Reports</h1>
</div>
<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <?php if (User::can('view-any-report')): ?>
        <th>Visible</th>
      <?php endif; ?>
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
        <?php if (User::can('view-any-report')): ?>
          <td>
            <?php if ($r->getVisible()): ?>
              <i class="icon-ok"></i>
            <?php endif; ?>
          </td>
        <?php endif; ?>
        <td><a href="<?php echo SITE_BASE; ?>/report/<?php echo $r->getId(); ?>"><?php echo fHTML::prepare($r->getTitle()); ?></a></td>
        <td><?php echo $r->getStartDatetime(); ?></td>
        <td><?php echo $r->getEndDatetime(); ?></td>
        <td><?php echo $r->getDuration(); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php
include(__DIR__ . '/../layout/footer.php');
