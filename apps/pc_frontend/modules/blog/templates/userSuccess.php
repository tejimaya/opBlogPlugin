<?php

include_parts(
  'BlogListPage',
  'blogUser',
  array(
    'title' => sprintf(__('Newest blog of %s'), $member->getName()),
    'list' => $blogList,
    'showName' => false
  )
);
