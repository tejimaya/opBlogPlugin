<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * openpneUpdateBlogRssCacheTask
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Masawa Nagasawa <nagasawa@tejimaya.com>
 */
class openpneUpdateBlogRssCacheTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'update-blog-rss-cache';
    $this->briefDescription = 'Updating blog rss cache';
    $this->detailedDescription = <<<EOF
The [openpne:update-blog-rss-cache|INFO].

  [./symfony opPlugin:update-blog-rss-cache|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->openDatabaseConnection();
    opDoctrineRecord::setDefaultCulture(sfConfig::get('default_culture', 'ja_JP'));
    sfContext::createInstance($this->createConfiguration('pc_frontend', 'prod'), 'pc_frontend');

    $startMemberId = sfConfig::get('next_update_blog_rss_cache_member_id', 1);
    $size = sfConfig::get('app_update_blog_rss_cache_limit', 0);

    $count = Doctrine::getTable('BlogRssCache')->update($startMemberId, $size);

    if ($count < $size)
    {
      $nextMemberId = 1;
    }
    else
    {
      $nextMemberId = $startMemberId + $size;
    }

    sfConfig::set('next_update_blog_rss_cache_member_id', $nextMemberId);
  }

  protected function openDatabaseConnection()
  {
    new sfDatabaseManager($this->configuration);
  }
}
