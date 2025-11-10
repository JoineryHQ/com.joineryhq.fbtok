<?php

use CRM_Fbhash_ExtensionUtil as E;

/**
 * General-purpose utilities for stepw extension.
 *
 */
class CRM_Fbhash_Utils_General {

  const HASH_SEPARATOR = '|';

  public static function getSetting($settingName, $default = NULL) {
    if (!isset(Civi::$statics[__CLASS__]['extSettings'])) {
      Civi::$statics[__CLASS__]['extSettings'] = \Civi::settings()->get(E::LONG_NAME);
    }
    return (Civi::$statics[__CLASS__]['extSettings'][$settingName] ?? $default);
  }

  public static function getHashedFilters($afformName) {
    $allFilters = CRM_Fbhash_Utils_General::getSetting('fbhash_hashedFilters');
    return ($allFilters[$afformName] ?? []);
  }

  public static function hashValue($value) {
    $salt = CIVICRM_SITE_KEY;
    $saltedValue = $value . $salt;
    $fallbackHashAlgo = 'sha256';
    $hashAlgo = CRM_Fbhash_Utils_General::getSetting('algorithm', $fallbackHashAlgo);
    if (!in_array($hashAlgo, hash_algos())) {
      // invalid hash algo; use sha256
      $hashAlgo = $fallbackHashAlgo;
    }
    $ret = hash($hashAlgo, $saltedValue) . CRM_Fbhash_Utils_General::HASH_SEPARATOR . $value;
    return $ret;
  }

  public static function getPlainValue($hashedValue) {
    list($trash, $value) = explode(CRM_Fbhash_Utils_General::HASH_SEPARATOR, $hashedValue);
    if ($hashedValue == CRM_Fbhash_Utils_General::hashValue($value)) {
      return $value;
    }
    else {
      return FALSE;
    }
  }

  public static function dehashFilters($afformName, $filters) {
    $ret = $filters;
    foreach (CRM_Fbhash_Utils_General::getHashedFilters($afformName) as $filterKey) {
      // fixme: I once thought this was a good idea, but why?
      // if (!array_key_exists($filterKey, $filters)) {
      //   continue;
      // }
      if ($plainValue = CRM_Fbhash_Utils_General::getPlainValue($filters[$filterKey])) {
        $ret[$filterKey] = $plainValue;
      }
      else {
        $ret[$filterKey] = -1;
      }
    }
    return $ret;
  }

  public static function hashFilters($afformName, $filters) {
    $ret = $filters;
    foreach (CRM_Fbhash_Utils_General::getHashedFilters($afformName) as $filterKey) {
      if (!array_key_exists($filterKey, $filters)) {
        continue;
      }
      $ret[$filterKey] = CRM_Fbhash_Utils_General::hashValue($filters[$filterKey]);
    }
    return $ret;
  }

}
