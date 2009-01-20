<div id="<?php echo $id ?>" class="dparts box">
<div class="parts">

<div class="partsHeading">
<h3><?php echo $option['title'] ?></h3>
</div>

<div class="block"><div class="body">

<table>
<tbody>
<ul class="articleList">
<?php foreach($option['list'] as $res): ?>
<li>
<span class="date"><?php echo date( __('m/d'), $res['date']) ?></span>
<?php image_tag('articleList_maker.gif', array('alf' => '')) ?> 
<?php
echo '<a href="' . $res['link_to_external'] . '">' . $res['title'] . '</a>';
?>
<?php if ($option['showName']): ?>
(<?php echo $res['name'] ?>)
<?php endif ?>
</li>
<?php endforeach; ?>
</ul>

<?php if (isset($option['moreInfo'])): ?>
<div class="moreInfo"><ul class="moreInfo"><li>
<?php echo link_to(__('More info'), $option['moreInfo']) ?>
</li></ul></div>
<?php endif; ?>

</tbody>
</table>

</div></div>

</div></div>

