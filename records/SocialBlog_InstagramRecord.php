<?php
/**
 * Social Blog plugin for Craft CMS
 *
 * SocialBlog Instagram Record
 *
 *
 * @author    James C Garrett
 * @copyright Copyright (c) 2017 James C Garrett
 * @link      http://jamescgarrett.com
 * @package   SocialBlog
 * @since     1.0.0
 */

namespace Craft;

class SocialBlog_InstagramRecord extends BaseRecord
{
  /**
   * Returns the name of the database table the model is associated with (sans table prefix). By convention,
   * tables created by plugins should be prefixed with the plugin name and an underscore.
   *
   * @return string
   */
  public function getTableName()
  {
    return 'socialblog';
  }

    /**
     * Returns an array of attributes which map back to columns in the database table.
     *
     * @access protected
     * @return array
     */
   protected function defineAttributes()
    {
      return array(
        'fetch' => array(AttributeType::Number, 'default' => 0),
        'lastFetch' => array(AttributeType::DateTime),
        'lastFetchImageId' => array(AttributeType::String), 
      );
    }
}

// 170458
// a29ccaaeafe642018be7eaaef57aa66f
// 170458.a29ccaa.82ed279adbc44af38096874fc2e9c7ab