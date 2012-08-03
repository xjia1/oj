</div> <!-- /container -->
<script type="text/javascript" src="<?php echo SITE_BASE; ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_BASE; ?>/js/bootstrap.min.js"></script>
<?php if (isset($this->nav_class)): ?>
  <script type="text/javascript"> $(function(){ $('.nav-<?php echo $this->nav_class; ?>').addClass('active'); }); </script>
<?php endif; ?>
</body>
</html>
