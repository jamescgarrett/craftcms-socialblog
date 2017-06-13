<?php
/**
 * Social Blog plugin for Craft CMS
 *
 * SocialBlog Controller
 *
 *
 * @author    James C Garrett
 * @copyright Copyright (c) 2017 James C Garrett
 * @link      http://jamescgarrett.com
 * @package   SocialBlog
 * @since     1.0.0
 */

namespace Craft;

class SocialBlogController extends BaseController
{

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = array('actionIndex',
        );

    /**
     * Handle a request going to our plugin's index action URL, e.g.: actions/socialBlog
     */
    public function actionIndex()
    {
    }
}