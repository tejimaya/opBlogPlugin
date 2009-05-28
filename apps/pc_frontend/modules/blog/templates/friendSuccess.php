<?php

include_parts(
  'BlogListPage',
  'blogIndex',
  array(
    'title' => __('Friends Newest blog'),
    'list' => $sf_data->getRaw('blogList'),
    'showName' => true
  )
);
