<?php
/**
 * Social Blog plugin for Craft CMS
 *
 * SocialBlog Instagram Model
 *
 *
 * @author    James C Garrett
 * @copyright Copyright (c) 2017 James C Garrett
 * @link      http://jamescgarrett.com
 * @package   SocialBlog
 * @since     1.0.0
 */

namespace Craft;

class SocialBlog_InstagramModel extends BaseElementModel
{
  /**
   * Defines this model's attributes.
   *
   * @return array
   */
  protected function defineAttributes()
  {
    return array_merge(parent::defineAttributes(), array(
      'fetch' => array(AttributeType::Number, 'default' => 0),
      'lastFetch' => array(AttributeType::DateTime),
      'lastFetchImageId' => array(AttributeType::String),
    ));
  }

  /**
   * Returns whether the current user can edit the element.
   *
   * @return bool
   */
  public function isEditable()
  {
    return false;
  }
}