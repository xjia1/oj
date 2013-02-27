<?php if (fAuthorization::checkLoggedIn() and Registration::has(fAuthorization::getUserToken(), $this->report->getId())): ?>
  <button class="btn btn-mini btn-success disabled">已确认参赛</button>
<?php elseif ($this->report->isRegistrable()): ?>
  <form style="display:inline;margin:0" action="<?php echo SITE_BASE; ?>/contest/<?php echo $this->report->getId(); ?>/register" method="POST">
    <button type="submit" class="btn btn-mini btn-success">确认参赛</button>
  </form>
<?php endif; ?>
