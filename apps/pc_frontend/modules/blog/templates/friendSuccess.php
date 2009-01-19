<?php

include_parts(
  'BlogListPage',
  'blogFriend',
  array(
    'title' => __('Friends newest blog'),
    'list' => $blogList,
    'showName' => true
  )
);
