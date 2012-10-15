<?php
$title = $this->page_title;
include(__DIR__ . '/../layout/header.php');
?>
<article><?php echo Markdown($this->page_content); ?></article>
<?php
include(__DIR__ . '/../layout/footer.php');
