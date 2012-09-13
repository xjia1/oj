<?php if (fAuthorization::checkLoggedIn() and Registration::has(fAuthorization::getUserToken(), $this->report->getId())): ?>
  <button class="btn btn-mini btn-success disabled">已经注册</button>
<?php elseif ($this->report->isRegistrable()): ?>
  <form style="display:inline;margin:0" action="<?php echo SITE_BASE; ?>/contest/<?php echo $this->report->getId(); ?>/register" method="POST">
    <button type="submit" class="btn btn-mini btn-success">注册参赛</button>
  </form>
<?php else: ?>
  <button class="btn btn-mini disabled">禁止注册</button>
<?php endif; ?>