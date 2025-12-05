<?php

/*
 * Settings metadata file
 */

use CRM_Fbtok_ExtensionUtil as E;

return [
  'com.joineryhq.fbtok.tokenEntropyBytes' => [
    'group_name' => 'com.joineryhq.fbtok',
    'name' => 'com.joineryhq.fbtok.tokenEntropyBytes',
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
  'com.joineryhq.fbtok.tokenizedFilters' => [
    'group_name' => 'com.joineryhq.fbtok',
    'name' => 'com.joineryhq.fbtok.tokenizedFilters',
    'type' => 'Array',
    'html_type' => 'textarea',
    'default' => [],
    'add' => '5.0',
    'title' => E::ts('Filters to be tokenized, per afform'),
    'is_domain' => 0,
    'is_contact' => 0,
    'description' => E::ts('Array of filters per afform.'),
    'help_text' => NULL,
  ],
  'com.joineryhq.fbtok.hmacKey' => [
    'group_name' => 'com.joineryhq.fbtok',
    'name' => 'com.joineryhq.fbtok.hmacKey',
    'type' => 'String',
    'html_type' => 'textarea',
    'default' => NULL,
    'add' => '5.0',
    'title' => E::ts('Fbtok HMAC Key'),
    'is_domain' => 0,
    'is_contact' => 0,
    'description' => E::ts('Secret key used for HMAC creation.'),
    'help_text' => NULL,
  ],
];
