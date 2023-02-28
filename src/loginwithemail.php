<?php
/**
 * @package     Login with email
 * @author      ClickFWD <https://clickfwd.com>
 * @copyright   Copyright (C) 2010-2023. ClickFWD LLC. All rights reserved.
 * @license     GNU GPL version 3 or later
 */
 
 defined('_JEXEC') or die;
 
 use Joomla\CMS\Plugin\CMSPlugin;
 
 class plgSystemLoginwithemail extends CMSPlugin
 {
     protected $app;
 
     protected $db;
 
     /**
      * @return void
      */
     public function onAfterRoute()
     {
         if (
             $this->app->input->getMethod() !== 'POST'
             ||
             $this->app->input->get('option') !== 'com_users'
             ||
             $this->app->input->get('task') !== 'user.login'
         ) {
             return;
         }
 
         $input = $this->app->input->getInputForRequestMethod();
 
         $email = $input->getEmail('username');
 
         if (strpos($email, '@') === false) {
             return;
         }
 
         $db = $this->db;
 
         $query = $db->getQuery(true)
             ->select($db->quoteName('username'))
             ->from($db->quoteName('#__users'))
             ->where($db->quoteName('email') . ' = :email')
             ->bind(':email', $email);
 
         $db->setQuery($query);
         
         $username = $db->loadResult();
 
         if (! $username) {
             return;
         }
         
         $input->set('username', $username);
     }
 }
 