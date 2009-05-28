<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opBlogPlugin
{
  static function getFeedByUrl($url)
  {
    if (is_null($url))
    {
      return false;
    }
    $old = umask(0);
    $feed = new SimplePie();
    $dir = sfConfig::get('sf_app_cache_dir') . '/plugins';
    if (!file_exists($dir))
    {
      if (!@mkdir($dir, 0777, true))
      {
        throw new Exception(sprintf('Could not create directory "%s"', $dir));
      }
    }
    $dir .= '/opBlogPlugin';
    if (!file_exists($dir))
    {
      if (!@mkdir($dir, 0777, true))
      {
        throw new Exception(sprintf('Could not create directory "%s"', $dir));
      }
    }
    umask($old);
    $feed->set_cache_location($dir);
    $feed->set_feed_url($url);
    if(!@$feed->init())
    {
      return false;
    }
    $feed->handle_content_type();

    return $feed;
  }

  static function getBlogListByMemberId($member_id, &$list)
  {
    $member = Doctrine::getTable('Member')->find($member_id);
    if (!$member || !$member->getIsActive())
    {
      return;
    }

    $feed = self::getFeedByUrl($member->getConfig('blog_url'));
    if (!$feed)
    {
      return;
    }

    foreach ($feed->get_items() as $item)
    {
      $list[] = self::setBlog(
        strtotime(@$item->get_date()),
        @$item->get_title(),
        @$item->get_link(),
        $member->getName()
      ); 
    }
  }

  static function setBlog($date, $title, $link, $name)
  {
    return array(
      'date' => $date,
      'title' => htmlspecialchars_decode($title),
      'link_to_external' => $link,
      'name' => $name
    );
  }

  static function sortBlogList(&$list, $size = 20)
  {
    foreach ($list as $aKey => $a)
    {
      $pickKey = $aKey;
      for ($bKey = $aKey + 1; $bKey < count($list); $bKey++)
      {
        if ($list[$bKey]['date'] > $list[$pickKey]['date'])
        {
          $pickKey = $bKey;
        }
      }
      if ($aKey != $pickKey)
      {
        $list[$aKey] = $list[$pickKey];
        $list[$pickKey] = $a;
      }
    }
    return array_splice($list, 0, $size);
  }

  static function limitBlogTitle(&$list)
  {
    foreach($list as &$res)
    {
      $res['title'] = mb_strcut($res['title'], 0, 30);
    }
  }

  static function getBlogListOfFriends($member_id, $size = 20, $limitTitle = false)
  {
    $member = Doctrine::getTable('Member')->find($member_id);
    $friendList = $member->getFriends();

    $list = array();
    foreach ($friendList as $friend)
    {
      self::getBlogListByMemberId($friend->getId(), $list);
    }
    $list = self::sortBlogList($list, $size);

    if ($limitTitle)
    {
      self::limitBlogTitle($list);
    }

    return $list;
  }

  static function getBlogListOfMember($member_id, $size = 20, $limitTitle = false)
  {
    $list = array();
    self::getBlogListByMemberId($member_id, $list);
    $list = self::sortBlogList($list, $size);
    if ($limitTitle)
    {
      self::limitBlogTitle($list);
    }

    return $list;
  }

  static function getBlogListOfAllMember($size = 20, $limitTitle = false)
  {
    $memberList = Doctrine::getTable('Member')->createQuery()->execute();

    $list = array();
    foreach ($memberList as $member)
    {
      self::getBlogListByMemberId($member->getId(), $list);
    }

    $list = self::sortBlogList($list, $size);
    if ($limitTitle)
    {
      self::limitBlogTitle($list);
    }

    return $list;
  }
}
