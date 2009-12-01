<ul class="articleList">
<?php foreach($options['blogRssCacheList'] as $blogRssCache): ?>
<li>
<span class="date"><?php echo op_format_date($blogRssCache->getDate(), 'XShortDateJa') ?></span>
<?php echo image_tag('articleList_maker.gif', array('alt' => '')) ?> 
<?php
echo link_to(op_truncate($blogRssCache->getTitle(), 30), $blogRssCache->getLink());
?>
<?php if ($options['showName']): ?>
(<?php echo $blogRssCache->getName() ?>)
<?php endif ?>
</li>
<?php endforeach; ?>
</ul>
