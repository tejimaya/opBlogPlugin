<?php
/**
 * introfriend components.
 *
 * @package    OpenPNE
 * @subpackage blog
 * @author     Masato Nagasawa <nagasawa@tejimaya.com>
 */
class blogComponents extends sfComponents
{
  public function executeBlogHomeFriend()
  {
    $this->blogList = BlogPeer::getBlogListByFriend($this->getUser()->getMemberId());
  }

  public function executeBlogHomeUser()
  {
    $this->member = $this->getUser()->getMember();
    $this->blogList = array();
    BlogPeer::getBlogListByMemberId($this->getUser()->getMemberId(), $this->blogList);
  }

  public function executeBlogProfile($request)
  {
    $this->blogList = array();
    BlogPeer::getBlogListByMemberId($this->id, $this->blogList);
  }
}
