<div class="pagination pagination-centered" style="height: auto;">
  <ul>
    <?php if ($this->page == 1): ?>
      <li class="disabled"><a href="#">&laquo; 上一页</a></li>
    <?php else: ?>
      <li><a href="<?php echo $this->page_url; ?><?php echo $this->page - 1; ?>">&laquo; 上一页</a></li>
    <?php endif; ?>
    <?php for ($page_i = 1; $page_i <= $this->page_records->getPages(); $page_i++): ?>
      <li class="<?php if ($page_i == $this->page) echo 'active'; ?>">
        <a href="<?php echo $this->page_url; ?><?php echo $page_i; ?>"><?php echo $page_i; ?></a>
      </li>
    <?php endfor; ?>
    <?php if ($this->page == $this->page_records->getPages()): ?>
      <li class="disabled"><a href="#">下一页 &raquo;</a></li>
    <?php else: ?>
      <li><a href="<?php echo $this->page_url; ?><?php echo $this->page + 1; ?>">下一页 &raquo;</a></li>
    <?php endif; ?>
  </ul>
</div>