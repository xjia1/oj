<?php
$title = 'Report: ' . $this->report->getTitle();
$stylesheets = array('tablesorter');
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1>Report: <?php echo fHTML::prepare($this->report->getTitle()); ?></h1>
</div>
<div class="alert alert-info">
  Sort multiple columns simultaneously by 
  holding down the <strong>shift</strong> key and 
  clicking a second, third or even fourth column header!
</div>
<table id="userscores" class="tablesorter table table-bordered table-striped">
  <thead>
    <tr>
      <?php foreach ($this->board->getHeaders() as $header): ?>
        <th><?php echo $header; ?></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php for ($i = 1; $i <= $this->board->getRowCount(); $i++): ?>
      <tr>
        <?php foreach ($this->board->getRow($i) as $cell): ?>
          <td><?php echo $cell; ?></td>
        <?php endforeach; ?>
      </tr>
    <?php endfor; ?>
  </tbody>
  <tfoot>
    <tr>
      <?php foreach ($this->board->getFooters() as $footer): ?>
        <th><?php echo $footer; ?></th>
      <?php endforeach; ?>
    </tr>
  </tfoot>
</table>
<?php
$javascripts = array('jquery.tablesorter.min', 'board');
include(__DIR__ . '/../layout/footer.php');
