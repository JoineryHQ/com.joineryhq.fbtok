<?php

/*
 * Settings metadata file
 */

use CRM_Fbhash_ExtensionUtil as E;

return [
  'com.joineryhq.fbhash' => [
    'group_name' => 'com.joineryhq.fbhash',
    'name' => 'com.joineryhq.fbhash',
    'type' => 'Array',
    'html_type' => 'textarea',
    'default' => FALSE,
    'add' => '5.0',
    'title' => E::ts('Fbhash extension settings'),
    'is_domain' => 0,
    'is_contact' => 0,
    'description' => E::ts('Settings array for fbhash extension.'),
    'help_text' => NULL,
  ],
];
