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
 * @author     Masato Nagasawa <nagasawa@tejimaya.com>
 */

class blogActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request t
  */
  public function executeIndex($request)
  {
    $this->blogList = opBlogPlugin::getBlogListOfAllMember(sfConfig::get('app_blog_action_size'));
    if (!count($this->blogList))
    {
      return sfView::ALERT;
    }
  }

 /**
  * Executes friend action
  *
  * @param sfRequest $request A request object
  */
  public function executeFriend($request)
  {
    $this->blogList = opBlogPlugin::getBlogListOfFriends(
      $this->getUser()->getMemberId(),
      sfConfig::get('app_blog_action_size')
    );
    if (!count($this->blogList))
    {
      return sfView::ALERT;
    }
  }

 /**
  * Executes user action
  *
  * @param sfRequest $request A request object
  */
  public function executeUser($request)
  {
    $this->member = $this->getUser()->getMember();
    $this->blogList = opBlogPlugin::getBlogListOfMember(
      $this->getUser()->getMemberId(),
      sfConfig::get('app_blog_action_size')
    );
    if (!count($this->blogList))
    {
      return sfView::ALERT;
    }
  }
 /**
  * Executes profile action
  *
  * @param sfRequest $request A request object
  */
  public function executeProfile($request)
  {
    $this->id = $request->getParameter('id');
    $this->member = Doctrine::getTable('Member')->find($this->id);
    if (!$this->member)
    {
      return sfView::ERROR;
    }
    $this->blogList = opBlogPlugin::getBlogListOfMember(
      $this->id,
      sfConfig::get('app_blog_action_size')
    );
    if (!count($this->blogList))
    {
      return sfView::ALERT;
    }
  }
}
