<?php
/**
 * Social Blog plugin for Craft CMS
 *
 * SocialBlog Instagram Service
 *
 *
 * @author    James C Garrett
 * @copyright Copyright (c) 2017 James C Garrett
 * @link      http://jamescgarrett.com
 * @package   SocialBlog
 * @since     1.0.0
 */

namespace Craft;

if (!class_exists('Instagram')) 
{
  require_once(CRAFT_PLUGINS_PATH . 'socialblog/src/Instagram.php');
}

class SocialBlog_InstagramService extends BaseApplicationComponent
{

  public function createPosts($data, $channel)
  {
    // get the images if check fetch approves
    // create posts with the fetch data checkFetch returns
    foreach ($data as $key => $row) 
    {
      $entry = new EntryModel(); 
      $entry->sectionId = $channel; 
      $entry->enabled = true;
      $entry->postDate = $row['created'];
      $entry->slug = 'instagram-' . strtolower(date('F-m-Y'));
      $entry->getContent()->setAttributes(array(     
        'title' => 'Instagram - ' . date('F m, Y', $row['created']) . ' at ' . date('g:i A', $row['created']),     
        'body' => $row['caption'],
        'image' => $row['image_large'],
        'instagramLink' => $row['link'],
      ));
      $success = craft()->entries->saveEntry($entry);
    }
  }

  public function checkFetch()
  {

    // get settings
    $settings = craft()->plugins->getPlugin('socialBlog')->getSettings();

    // get fetch details
    $fetchDetails = $this->getFetchDetails();

    // fetch data
    if ($settings->instagramClientId == '' || $settings->instagramAccessToken == '' || $settings->instagramClientSecret == '') 
    { 
      return false; 
    }
    $instagram = new \JcG\Instagram\Instagram($settings->instagramClientId, $settings->instagramClientSecret, $settings->instagramAccessToken);

    $data = array();

    try 
    {
      $params = array('min_id' => $fetchDetails['lastFetchImageId']);
      $results = $instagram->get('users/' . $settings->instagramUserId . '/media/recent', $params);
      SocialBlogPlugin::log(print_r($results, true));
      if ($results != null && $results->meta->code == 200):
        foreach ($results->data as $result):
          $data[] = array(
            "id" => $result->id,
            "caption" => (isset($result->caption->text ) ? $result->caption->text : ""),
            "image_large" => $result->images->standard_resolution->url,
            "created" => $result->created_time,
            "link" => $result->link,
          );
        endforeach;
      endif;
    }
    catch(Exception $e)
    {
      throw new Exception($e);
    }

    // get fetch store and check whether 30 minutes old and new images
    if (!$fetchDetails || strtotime($fetchDetails['lastFetch']) < strtotime('-30 minutes')
      && $fetchDetails['lastFetchImageId'] == $data[0]['id'])
    {
      return false;
    }

    // save the new fetch details
    $dateTime = new DateTime();
    $now = $dateTime->format('Y-m-d H:i:s');
    $this->saveFetch($now, $data[0]['id']);

    // order data by date created
    $imagesOrdered = array();
    foreach ($data as $key => $row) 
    {
      $imagesOrdered[$key] = strtotime($row['created']);
    }
    array_multisort($imagesOrdered, SORT_DESC, $data);

    // all this is good, create the posts with the data
    $this->createPosts($data, $settings->channelToPopulate);
  }

  public function getFetchDetails()
  {
    $model = $this->getFetchStore();
    if (!$model) {
      return false;
    }
    return $model->attributes;
  }

  public function getFetchStore()
  {
    $record = SocialBlog_InstagramRecord::model()->findByAttributes(array("fetch" => 0));
    if ($record): 
      $model =  SocialBlog_InstagramModel::populateModel($record);
    else:
      $record = new SocialBlog_InstagramRecord();
      $model =  SocialBlog_InstagramModel::populateModel($record);
    endif;

    return $model;
  }

  public function saveFetch($fetchTime, $fetchImageId) 
  {
    // save the fetch data from checkUpdate
    $model = $this->getFetchStore();
    if (!$model) 
    { 
      return false; 
    }

    $record = SocialBlog_InstagramRecord::model()->findByAttributes(array("fetch" => 0));
    if (!$record) 
    { 
      $record = new SocialBlog_InstagramRecord(); 
    }

    $model->lastFetch = $fetchTime;
    $model->lastFetchImageId = $fetchImageId;

    $record->setAttributes($model->getAttributes(), false);
    
    try
    {
      $record->validate();
      $model->addErrors($record->getErrors());

      if (!$model->hasErrors()):
        $record->save(false);
        $model->setAttribute('id', $record->getAttribute('id'));
        return true;
      else:
        return false;
      endif;

    }
    catch (\Exception $ex)
    {
      throw $ex;
    }
  }
}