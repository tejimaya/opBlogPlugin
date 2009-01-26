<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class BlogPeer
{
  public static function getXmlByUrl($url)
  {
    if (is_null($url) || $url === '')
    {
      return false;
    }
    $root = @simplexml_load_file($url);
    if (!$root)
    {
      return false;
    }
    return $root;
  }

  public static function getFeedType($root)
  {
    if (!$root)
    {
      return false;
    }
    switch (strtolower($root->getName()))
    {
      case "rdf":
        $feedType = "rdf";
        // rss0.8, rss1.0
        break;
      case "rss":
        $feedType = "rss";
        // rss2.0
        break;
      case "feed":
        $feedType = "atom";
        // atom
        break;
      default:
        $feedType = false;
        break;
    }
    return $feedType;
  }

  public static function getBlogListByMemberId($member_id, &$list)
  {
    $member = MemberPeer::retrieveByPk($member_id);
    $root = self::getXmlByUrl($member->getConfig('blog_url'));

    $feedType = BlogPeer::getFeedType($root);

    switch ($feedType)
    {
    case 'rdf':
      self::addBlogFromRdf($member, $root, $list);
      break; 
    case 'rss':
      self::addBlogFromRss($member, $root, $list);
      break;
    case 'atom':
      self::addBlogFromAtom($member, $root, $list);
      break;
    default:
      break;
    }
  }

  protected static function addBlogFromRdf(&$member, &$root, &$list)
  {
    foreach ($root->item as $item)
    {
      $dc = $item->children('http://purl.org/dc/elements/1.1/');
      $list[] = self::setBlog(
        strtotime(strval($dc->date)),
        strval($item->title),
        strval($item->link),
        $member->getName()
      );
    }
  }

  protected static function addBlogFromRss(&$member, &$root, &$list)
  {
    foreach ($root->channel->item as $item)
    {
      $list[] = self::setBlog(
        strtotime(strval($item->pubDate)),
        strval($item->title),
        strval($item->link),
        $member->getName()
      );
    }
  }

  protected static function addBlogFromAtom(&$member, &$root, &$list)
  {
    foreach ($root->entry as $entry)
    {
      $list[] = self::setBlog(
        strtotime(strval($entry->published)),
        strval($entry->title),
        strval($entry->link),
        $member->getName()
      );
    }
  }

  protected static function setBlog($date, $title, $link, $name)
  {
    return array(
      'date' => $date,
      'title' => $title,
      'link_to_external' => $link,
      'name' => $name
    );
  }

  public static function sortBlogList(&$list, $size = 20)
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

  public static function limitBlogTitle(&$list)
  {
    foreach($list as &$res)
    {
      $res['title'] = mb_strcut($res['title'], 0, 30);
    }
  }

  public static function getBlogListOfFriend($member_id, $size=20, $limitTitle = false)
  {
    $c = new Criteria();
    $c->add(MemberRelationshipPeer::MEMBER_ID_TO, $member_id);
    $c->add(MemberRelationshipPeer::IS_FRIEND, true);
    $c->addSelectColumn(MemberRelationshipPeer::MEMBER_ID_FROM);
    $stmt = MemberRelationshipPeer::doSelectStmt($c);
    $list = array();
    while($id = $stmt->fetchColumn(0))
    {
      self::getBlogListByMemberId($id, $list);
    }
    $list = self::sortBlogList($list, $size);
    if ($limitTitle)
    {
      self::limitBlogTitle($list);
    }
    
    return $list;
  }

  public static function getBlogListOfMember($member_id, $size=20, $limitTitle = false)
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

  public static function getBlogListOfAllMember($size=20, $limitTitle = false)
  {
    $c = new Criteria();
    $c->addSelectColumn(MemberPeer::ID);
    $stmt = MemberPeer::doSelectStmt($c);
    $list = array();
    while($id = $stmt->fetchColumn(0))
    {
      self::getBlogListByMemberId($id, $list);
    }
    $list = self::sortBlogList($list, $size);
    if ($limitTitle)
    {
      self::limitBlogTitle($list);
    }
    
    return $list;
  }
}
