<?php
$title = 'Report: ' . $this->report->getTitle();
include(__DIR__ . '/../layout/header.php');
?>
<h1>Report: <?php echo fHTML::prepare($this->report->getTitle()); ?></h1>
<?php
include(__DIR__ . '/../layout/footer.php');
