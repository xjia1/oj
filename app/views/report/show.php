<?php
$title = $this->report->getTitle();
$stylesheets = array('tablesorter');
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1>
    <?php echo fHTML::prepare($this->report->getTitle()); ?>
    <small>
      <?php echo $this->report->getStartDatetime(); ?>
      --
      <?php echo $this->report->getEndDatetime(); ?>
      (<?php echo $this->report->getDuration(); ?>)
    </small>
    <?php include(__DIR__ . '/_register_btn.php'); ?>
  </h1>
</div>
<div class="row">
  <div class="progress progress-striped active span10">
    <div class="bar" style="width: <?php echo $this->report->getElapsedRatio(); ?>%;"></div>
  </div>
  <div class="span2">
    <i class="icon-time"></i>
    时间：已经过 <?php echo $this->report->getElapsedRatio(); ?>%
  </div>
</div>
<hr>
<div id="problems">
  <h2>题目</h2>
  <?php foreach ($this->report->getProblems() as $problem_id): ?>
    <a class="btn" target="_blank" href="<?php echo SITE_BASE; ?>/problem/<?php echo $problem_id; ?>"><?php echo $problem_id; ?></a>
  <?php endforeach; ?>
</div>
<hr>
<div id="clarification">
  <h2>Q &amp; A</h2>
  <table id="questions" class="table table-striped">
    <thead>
      <tr>
        <th>分类</th>
        <th>提问时间</th>
        <th>问题</th>
        <th>回复时间</th>
        <th>回复</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($this->report->fetchQuestions() as $question): ?>
        <tr>
          <td><?php echo $question->getCategoryName(); ?></td>
          <td class="timestamp"><?php echo $question->getAskTime(); ?></td>
          <td><?php echo fHTML::encode($question->getQuestion()); ?></td>
          <?php if (!strlen($question->getAnswer()) and $this->report->allowAnswer()): ?>
            <td colspan="2">
              <form class="form-inline" action="<?php echo SITE_BASE; ?>/question/<?php echo $question->getId(); ?>/reply" method="POST">
                <textarea name="reply"></textarea>
                <button type="submit" class="btn btn-mini">回复</button>
              </form>
            </td>
          <?php else: ?>
            <td class="timestamp"><?php echo $question->getAnswerTime(); ?></td>
            <td><?php echo fHTML::prepare($question->getAnswer()); ?></td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <?php if ($this->report->allowQuestion()): ?>
    <tfoot>
      <tr>
        <td colspan="5">
          <a href="#question_modal" role="button" class="btn" data-toggle="modal">提问</a>
        </td>
      </tr>
    </tfoot>
    <?php endif; ?>
  </table><!-- /#questions -->
  <div id="question_modal" class="modal hide fade">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3>提问</h3>
    </div><!-- /.modal-header -->
    <form class="form-horizontal" method="POST" action="<?php echo SITE_BASE; ?>/contest/<?php echo $this->report->getId(); ?>/question">
      <div class="modal-body">
        <div class="control-group">
          <label class="control-label" for="category">分类</label>
          <div class="controls">
            <select id="category" name="category">
              <option value="0">（请选择）</option>
              <?php foreach ($this->report->getProblems() as $problem_id): ?>
                <option value="<?php echo $problem_id; ?>">题目：<?php echo $problem_id; ?></option>
              <?php endforeach; ?>
              <option value="<?php echo Question::ABOUT_CONTEST_HIDDEN; ?>">关于比赛</option>
              <option value="<?php echo Question::ABOUT_SYSTEM_HIDDEN; ?>">关于系统使用</option>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="question">问题</label>
          <div class="controls">
            <textarea id="question" rows="10" name="question" style="font-family: Monaco, Lucida Console, Courier New, Free Monospaced;"></textarea>
            <p class="help-block">长度限制：500字节。</p>
          </div>
        </div>
      </div><!-- /.modal-body -->
      <div class="modal-footer">
        <div class="control-group">
          <div class="controls">
            <button type="submit" class="btn btn-primary">提交</button>
            <button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>
          </div>
        </div>
      </div><!-- /.modal-footer -->
    </form>
  </div><!-- /#question_modal -->
</div><!-- /#clarification -->
<?php if ($this->report->isStarted() and count($this->report->getUsernames())): ?>
<hr>
<h2>排名</h2>
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
<div class="alert alert-info">
  Sort multiple columns simultaneously by 
  holding down the <strong>shift</strong> key and 
  clicking a second, third or even fourth column header!
</div>
<?php endif; ?>
<?php
$contest_id = $this->report->getId();
$meta_refresh = Variable::getInteger('status-refresh', 30);
$javascripts = array('jquery.tablesorter.min', 'board', 'ts-alert');
include(__DIR__ . '/../layout/footer.php');
