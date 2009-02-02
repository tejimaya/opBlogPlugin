<?php

if (count($blogList))
{
  include_parts(
    'BlogListBox',
    'blogHomeUser_'.$gadget->getId(),
    array(
      'title' => sprintf(__('Newest blog of %s'), $member->getName()),
      'list' => $blogList,
      'showName' => false,
      'moreInfo' => 'blog/user'
    )
  );
}
