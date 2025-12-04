<?php

/*
 * Settings metadata file
 */

use CRM_Fbhash_ExtensionUtil as E;

return [
  'com.joineryhq.fbhash.tokenEntropyBytes' => [
    'group_name' => 'com.joineryhq.fbhash',
    'name' => 'com.joineryhq.fbhash.tokenEntropyBytes',
    'type' => 'Integer',
    'html_type' => 'text',
    'default' => 8,
    'add' => '5.0',
    'title' => E::ts('HMAC entropy in bytes'),
    'is_domain' => 0,
    'is_contact' => 0,
    'description' => E::ts('Bytes of entropy to be retained in HMAC.'),
    'help_text' => NULL,
  ],
  'com.joineryhq.fbhash.hashedFilters' => [
    'group_name' => 'com.joineryhq.fbhash',
    'name' => 'com.joineryhq.fbhash.hashedFilters',
    'type' => 'Array',
    'html_type' => 'textarea',
    'default' => [],
    'add' => '5.0',
    'title' => E::ts('Filters to be hashed, per afform'),
    'is_domain' => 0,
    'is_contact' => 0,
    'description' => E::ts('Array of filters per afform.'),
    'help_text' => NULL,
  ],
  'com.joineryhq.fbhash.hmacKey' => [
    'group_name' => 'com.joineryhq.fbhash',
    'name' => 'com.joineryhq.fbhash.hmacKey',
    'type' => 'String',
    'html_type' => 'textarea',
    'default' => NULL,
    'add' => '5.0',
    'title' => E::ts('Fbhash HMAC Key'),
    'is_domain' => 0,
    'is_contact' => 0,
    'description' => E::ts('Secret key used for HMAC creation.'),
    'help_text' => NULL,
  ],
];
