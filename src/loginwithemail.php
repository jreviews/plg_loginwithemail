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
         ) {
             return;
         }

         if ($this->app->input->get('option') == 'com_users' 
            && $this->app->input->get('task') == 'user.login') {
            $this->loginForm();
         }
 
        if ($this->app->input->get('task') == 'reset.confirm') {
            $this->passwordResetForm();
        }
     }

     protected function loginForm()
     {
        $input = $this->app->input->getInputForRequestMethod();
 
        $email = $input->getEmail('username');

        $username = $this->getUsernameFromEmail($email);
        
        $input->set('username', $username);
     }

     protected function passwordResetForm()
     {
        $data = $this->app->input->getVar('jform');
        
        $username = $this->getUsernameFromEmail($data['username'] ?? null);

        $data['username'] = $username;
        
        $this->app->input->set('jform', $data);
     }     

     protected function getUsernameFromEmail($email)
     {
        if (strpos($email, '@') === false) {
            return $email;
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
            return $email;
        }

        return $username;
     }
 }
 