<?php
$title = '评测状态';
$stylesheets = array('verdicts');
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <form method="GET" action="<?php echo SITE_BASE; ?>/status" class="pull-right form-search">
    <input type="text" class="input-small search-query" placeholder="提交者" name="owner" maxlength="100" value="<?php echo $this->owner; ?>">
    <input type="number" class="input-small search-query" placeholder="题目编号" name="problem" maxlength="10" value="<?php echo $this->problem_id; ?>">
    <select name="language" class="input-small search-query">
    <?php
      $languages = array('语言', 'C++', 'C', 'Java');
      foreach ($languages as $value => $text) {
        fHTML::printOption($text, $value, $this->language);
      }
    ?>
    </select>
    <select name="verdict" class="input-medium search-query">
    <?php
      $verdicts = array('结果', '正确', '格式错误', '超过时间限制', '超过内存限制', '答案错误', '运行时错误', '超过输出限制', '编译错误', '系统错误', '校验错误');
      foreach ($verdicts as $value => $text) {
        fHTML::printOption($text, $value, $this->verdict);
      }
    ?>
    </select>
    <button type="submit" class="btn btn-primary">
      <i class="icon-filter icon-white"></i> 过滤
    </button>
    <?php if (strlen($this->owner) or strlen($this->problem_id) or !empty($this->language) or !empty($this->verdict)): ?>
      <a class="btn" href="<?php echo SITE_BASE; ?>/status">取消</a>
    <?php endif; ?>
  </form>
  <h1><?php echo $title; ?></h1>
</div>
<table id="status" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>运行编号</th>
      <th>提交者</th>
      <th class="what">题目编号</th>
      <th>评测结果</th>
      <?php if (fAuthorization::checkLoggedIn()): ?>
        <th>分数</th>
      <?php endif; ?>
      <th>时间</th>
      <th>内存</th>
      <th>语言</th>
      <th>提交时间</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->records as $r): ?>
      <tr>
        <td><a href="<?php echo $this->top_url; ?><?php echo $r->getId(); ?>"><?php echo $r->getId(); ?></a></td>
        <td><?php echo fHTML::encode($r->getOwner()); ?> <?php echo fHTML::encode(Profile::fetchRealName($r->getOwner())); ?></td>
        <td>
          <a href="<?php echo SITE_BASE; ?>/problem/<?php echo $r->getProblemId(); ?>"><?php echo $r->getProblemId(); ?></a>
          <?php if (fAuthorization::checkLoggedIn()): ?>
            <a href="<?php echo SITE_BASE; ?>/submit?problem=<?php echo $r->getProblemId(); ?>" class="icon-repeat"></a>
          <?php endif; ?>
        </td>
        <td>
          <?php if ($r->isReadable()): ?>
            <a class="record<?php echo str_replace(' ', '', $r->getResult()); ?>" 
               href="<?php echo SITE_BASE; ?>/record/<?php echo $r->getId(); ?>"><?php echo $r->getTranslatedResult(); ?></a>
          <?php else: ?>
            <span class="record<?php echo str_replace(' ', '', $r->getResult()); ?>"><?php echo $r->getTranslatedResult(); ?></span>
          <?php endif; ?>
        </td>
        <?php if (fAuthorization::checkLoggedIn()): ?>
          <td>
            <?php if ($r->isReadable()): ?>
              <?php echo $r->getScore(); ?>
            <?php else: ?>
              -
            <?php endif; ?>
          </td>
        <?php endif; ?>
        <td><?php echo $r->getTimeCost(); ?></td>
        <td><?php echo $r->getMemoryCost(); ?></td>
        <td><?php echo fHTML::encode($r->getLanguageName()); ?></td>
        <td><?php echo $r->getSubmitDatetime(); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php
$meta_refresh = Variable::getInteger('status-refresh', 30);
include(__DIR__ . '/../layout/footer.php');
