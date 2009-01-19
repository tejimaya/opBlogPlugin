<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * blog actions.
 *
 * @package    OpenPNE
 * @subpackage blog
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class blogActions extends sfActions
{
 /**
  * Executes friend action
  *
  * @param sfRequest $request A request object
  */
  public function executeFriend($request)
  {
    $this->blogList = BlogPeer::getBlogListByFriend($this->getUser()->getMemberId(), 20);
  }

 /**
  * Executes user action
  *
  * @param sfRequest $request A request object
  */
  public function executeUser($request)
  {
    $this->member = $this->getUser()->getMember();
    $this->blogList = array();
    BlogPeer::getBlogListByMemberId($this->getUser()->getMemberId(), $this->blogList, 20);
  }
}
