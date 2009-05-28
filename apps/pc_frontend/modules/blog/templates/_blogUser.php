<?php

if (count($blogList))
{
  include_parts(
    'BlogListBox',
    'blogUser_'.$gadget->getId(),
    array(
      'title' => sprintf(__('Newest blog of %s'), $member->getName()),
      'list' => $sf_data->getRaw('blogList'),
      'showName' => false,
      'moreInfo' => 'blog/user'
    )
  );
}
