<?php

op_include_parts(
  'BlogListPage',
  'blogIndex',
  array(
    'class' => 'recentList',
    'title' => __('Friends Newest blog'),
    'blogRssCacheList' => $blogRssCacheList,
    'showName' => true
  )
);
