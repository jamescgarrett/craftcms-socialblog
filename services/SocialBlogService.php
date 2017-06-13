<?php
/**
 * Social Blog plugin for Craft CMS
 *
 * SocialBlog Service
 *
 *
 * @author    James C Garrett
 * @copyright Copyright (c) 2017 James C Garrett
 * @link      http://jamescgarrett.com
 * @package   SocialBlog
 * @since     1.0.0
 */

namespace Craft;

class SocialBlogService extends BaseApplicationComponent
{
    public function log($dataToLog)
    {
        SocialBlogPlugin::log(print_r($dataToLog, true));
    }
}