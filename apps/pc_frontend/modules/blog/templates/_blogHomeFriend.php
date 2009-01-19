<?php

if (count($blogList))
{
  include_parts(
    'BlogListBox',
    'blogHomeFriend',
    array(
      'title' => __('Friends newest blog'),
      'list' => $blogList,
      'showName' => true,
      'moreInfo' => 'blog/friend'
    )
  );
}
