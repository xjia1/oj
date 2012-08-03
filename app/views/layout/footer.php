<hr>
<footer>
  Powered by <a href="http://php.net/">PHP Technologies</a> |
  <a href="<?php echo SITE_BASE; ?>/page/contributors">Contributors</a>
  <br>
  Copyright &copy; 2011-2012 <a href="http://acm.sjtu.edu.cn/">ACM Class</a>.
  All rights reserved.
</footer>
</div> <!-- /container -->
<script type="text/javascript" src="<?php echo SITE_BASE; ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_BASE; ?>/js/bootstrap.min.js"></script>
<?php if (isset($this->nav_class)): ?>
  <script type="text/javascript"> $(function(){ $('.nav-<?php echo $this->nav_class; ?>').addClass('active'); }); </script>
<?php endif; ?>
</body>
</html>
