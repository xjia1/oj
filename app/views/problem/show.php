<?php
$title = $this->problem->getTitle();
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1><?php echo $this->problem->getId(); ?>. <?php echo fHTML::encode($this->problem->getTitle()); ?></h1>
</div>
<div class="row">
  <article class="span9"><?php echo Markdown($this->problem->getDescription()); ?></article>
  <aside class="span3">
    <div class="well">
      <a class="btn btn-primary btn-large" href="<?php echo SITE_BASE; ?>/submit?problem=<?php echo $this->problem->getId(); ?>">提交此题</a>
    </div>
  </aside>
</div>
<script type="text/x-mathjax-config">
MathJax.Hub.Config({
    tex2jax: {
        inlineMath: [ ['$','$'], ["\\(","\\)"]  ],
        processEscapes: true,
        skipTags: ['script', 'noscript', 'style', 'textarea', 'pre', 'code']
    }
});

MathJax.Hub.Queue(function() {
    var all = MathJax.Hub.getAllJax(), i;
    for(i=0; i < all.length; i += 1) {
        all[i].SourceElement().parentNode.className += ' has-jax';
    }
});
</script>

<script async src="//cdn.bootcss.com/mathjax/2.7.0/MathJax.js?config=TeX-MML-AM_CHTML"></script>
<?php
include(__DIR__ . '/../layout/footer.php');
