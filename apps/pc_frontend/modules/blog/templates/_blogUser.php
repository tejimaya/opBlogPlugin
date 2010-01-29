<?php

if (count($blogRssCacheList))
{
  $param = '';
  if ($member->getId() != $sf_user->getMemberId())
  {
    $param = '?id='.$member->getId();
  }

  op_include_parts(
    'BlogListBox',
    'blogUser_'.$gadget->getId(),
    array(
      'class' => 'homeRecentList',
      'title' => sprintf(__('Newest blog of %s'), $member->getName()),
      'blogRssCacheList' => $blogRssCacheList,
      'showName' => false,
      'moreInfo' => array(link_to(__('More info'), 'blog/user'.$param))
    )
  );
}
