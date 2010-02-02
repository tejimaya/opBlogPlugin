<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

include dirname(__FILE__).'/../../bootstrap/functional.php';
include dirname(__FILE__).'/../../bootstrap/database.php';
include dirname(__FILE__).'/../../bootstrap/util.php';

setBlogUrl(1, FEED_URL);
setBlogUrl(2, FEED_URL);
setBlogUrl(3, FEED_URL);
addFriend(1, 2);
addFriend(1, 3);
addFriend(3, 1, true);
Doctrine::getTable('BlogRssCache')->updateByMemberId(1);
Doctrine::getTable('BlogRssCache')->updateByMemberId(2);
Doctrine::getTable('BlogRssCache')->updateByMemberId(3);

$test = new opTestFunctional(new sfBrowser());
$test->login('sns@example.com', 'password');
$test->setCulture('en');

$test->get('/blog')
  ->with('request')->begin()
    ->isParameter('module', 'blog')
    ->isParameter('action', 'index')
  ->end()

  ->with('response')->begin()
    ->checkElement('h3', 'Newest blog')
  ->end()

  ->get('/blog/user')
  ->with('request')->begin()
    ->isParameter('module', 'blog')
    ->isParameter('action', 'user')
  ->end()

  ->with('response')->begin()
    ->checkElement('h3', 'Newest blog of OpenPNE1')
  ->end()

  ->get('/blog/user/2')
  ->with('request')->begin()
    ->isParameter('module', 'blog')
    ->isParameter('action', 'user')
  ->end()

  ->with('response')->begin()
    ->checkElement('h3', 'Newest blog of OpenPNE2')
  ->end()

  ->get('/blog/user/3')
  ->with('response')->begin()
    ->checkElement('h3', NULL)
  ->end()

  ->get('/blog/friend')
  ->with('request')->begin()
    ->isParameter('module', 'blog')
    ->isParameter('action', 'friend')
  ->end()

  ->with('response')->begin()
    ->checkElement('h3', 'Friends Newest blog')
  ->end()
;
