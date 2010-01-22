<?php
/**
 */
class PluginBlogRssCacheTable extends Doctrine_Table
{
  public function deleteByMemberId($memberId)
  {
    Doctrine_Query::create()
      ->delete('BlogRssCache')
      ->where('member_id = ?', $memberId)
      ->execute();
  }

  public function update($startMemberId, $size = 0)
  {
    $q = Doctrine::getTable('MemberConfig')->createQuery()
      ->where('name = ?', 'blog_url')
      ->addWhere('member_id >= ?', $startMemberId)
      ->orderBy('member_id DESC');

    if ($size)
    {
      $q->limit($size);
    }
    $memberConfigList = $q->execute();

    foreach ($memberConfigList as $memberConfig)
    {
      $this->updateByMemberIdAndUrl($memberConfig->getMemberId(), $memberConfig->getValue());
    }

    return $memberConfigList->count();
  }

  public function updateByMemberId($memberId)
  {
    $memberConfig = Doctrine::getTable('MemberConfig')->findOneByNameAndMemberId('blog_url', $memberId);
    $this->updateByMemberIdAndUrl($memberId, $memberConfig->getValue());
  }

  public function getFriendBlogListByMemberId($memberId, $size = 20)
  {
    $memberList = Doctrine::getTable('Member')->find($memberId)->getFriends($size);
    $memberIdList = array();
    foreach ($memberList as $member)
    {
      $memberIdList[] = $member->getId();
    }

    if (!count($memberIdList))
    {
      return array();
    }

    return Doctrine::getTable('BlogRssCache')->createQuery()
      ->where('member_id IN ('.implode(',', $memberIdList).')')
      ->orderBy('date DESC')
      ->limit($size)
      ->execute();
  }

  public function getAllMembers($size = 20)
  {
    return Doctrine::getTable('BlogRssCache')->createQuery()
      ->orderBy('date DESC')
      ->limit($size)
      ->execute();
  }

  public function findByMember($member, $size = 20)
  {
    return $this->findByMemberId($member->getId(), $size);
  }

  public function findByMemberId($memberId, $size = 20)
  {
    return Doctrine::getTable('BlogRssCache')->createQuery()
      ->where('member_id = ?', $memberId)
      ->orderBy('date DESC')
      ->limit($size)
      ->execute();
  }

  protected function updateByMemberIdAndUrl($memberId, $url)
  {
    $feed = opBlogPlugin::getFeedByUrl($url);
    foreach ($feed as $item)
    {
      $blogRssCache = $this->findOneByMemberIdAndLink(
        $memberId,
        $item['link']
      );

      if (($blogRssCache && $blogRssCache->getUpdatedAt() == $item['date']) ||
        strtotime($item['date']) > time())
      {
        continue;
      }

      if (!$blogRssCache)
      {
        $blogRssCache = new BlogRssCache();
      }
      $blogRssCache->setMemberId($memberId);
      $blogRssCache->setTitle($item['title']);
      $blogRssCache->setDescription($item['description']);
      $blogRssCache->setLink($item['link']);
      $blogRssCache->setDate($item['date']);
      $blogRssCache->save();
    }

    return true;
  }
}
