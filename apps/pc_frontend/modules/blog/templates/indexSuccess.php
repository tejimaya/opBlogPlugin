<?php

include_parts(
  'BlogListPage',
  'blogFriend',
  array(
    'title' => __('Newest blog'),
    'list' => $blogList,
    'showName' => true
  )
);
