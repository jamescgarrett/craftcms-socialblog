<?php
/**
 * Social Blog plugin for Craft CMS
 *
 * Social Blog Variable
 *
 *
 * @author    James C Garrett
 * @copyright Copyright (c) 2017 James C Garrett
 * @link      http://jamescgarrett.com
 * @package   SocialBlog
 * @since     1.0.0
 */

namespace Craft;

class SocialBlogVariable
{
  public function checkInstagram()
  {
    return craft()->socialBlog_instagram->checkFetch();
  }
}