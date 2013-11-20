<?php
$title = '比赛列表';
include(__DIR__ . '/../layout/header.php');
?>
<div class="page-header">
  <h1><?php echo $title; ?></h1>
</div>
<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <?php if (User::can('view-any-report')): ?>
        <th>公开</th>
      <?php endif; ?>
      <th>标题</th>
      <th>题目数目</th>
      <th>开始时间</th>
      <th>结束时间</th>
      <th>比赛时长</th>
      <th colspan="2">参赛人数</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->reports as $r): ?>
      <tr>
        <td><?php echo $r->getId(); ?></td>
        <?php if (User::can('view-any-report')): ?>
          <td>
            <?php if ($r->getVisible()): ?>
              <i class="icon-ok"></i>
            <?php endif; ?>
          </td>
        <?php endif; ?>
        <td><a href="<?php echo SITE_BASE; ?>/contest/<?php echo $r->getId(); ?>"><?php echo fHTML::prepare($r->getTitle()); ?></a></td>
        <td><?php echo count($r->getProblems()); ?></td>
        <td><?php echo $r->getStartDatetime(); ?></td>
        <td><?php echo $r->getEndDatetime(); ?></td>
        <td><?php echo $r->getDuration(); ?></td>
        <td><?php echo $r->countRegistrants(); ?></td>
        <td><?php
          $this->report = $r;
          include(__DIR__ . '/_register_btn.php');
        ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php
include(__DIR__ . '/../layout/footer.php');
