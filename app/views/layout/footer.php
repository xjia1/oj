    <div id="push"></div>
  </div> <!-- /container -->
</div><!--wrap-->

<div class="footer">
  <div class="container">
  	<hr>
	Powered by <a href="http://php.net/">PHP Technologies</a> |
  	<a href="<?php echo SITE_BASE; ?>/page/contributors">Contributors</a>
  	<br>
  	Copyright &copy; 2011-2012 <a href="http://acm.sjtu.edu.cn/">ACM Class</a>.
  	All rights reserved.
  	<br>
  	<?php profiler_dump(); ?>
  </div>
</div>

<script src="<?php echo ASSET_JS; ?>/js/jquery.min.js"></script>
<script src="<?php echo ASSET_JS; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo ASSET_JS; ?>/js/prettify.js"></script>
<?php if (isset($contest_id)): ?>
<script> window.contest_id = '<?php echo $contest_id; ?>'; </script>
<?php endif; ?>
<?php if (isset($meta_refresh)): ?>
<script>
(function(){
  window.do_refresh = function() {
    window.location.reload(false);
  }
  var timer;
  function enable_refresh() {
    console.log('enable_refresh');
    timer = setTimeout("window.do_refresh()", <?php echo $meta_refresh; ?> * 1000);
  }
  function disable_refresh() {
    console.log('disable_refresh');
    clearTimeout(timer);
  }
  $(function(){
    $('.modal').on('show', disable_refresh).on('hide', enable_refresh);
    $('textarea').focus(disable_refresh).blur(enable_refresh);
    enable_refresh();
  });
})();
</script>
<?php endif; ?>
<?php if (isset($javascripts)): ?>
  <?php foreach ($javascripts as $javascript): ?>
    <script src="<?php echo ASSET_JS; ?>/js/<?php echo $javascript; ?>.js"></script>
  <?php endforeach; ?>
<?php endif; ?>
<?php if (isset($this->nav_class)): ?>
  <script> $(function(){ $('.nav-<?php echo $this->nav_class; ?>').addClass('active'); }); </script>
<?php endif; ?>
</body>
</html>
