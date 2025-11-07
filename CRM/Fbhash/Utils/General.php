<?php

use CRM_Fbhash_ExtensionUtil as E;

/**
 * General-purpose utilities for stepw extension.
 *
 */
class CRM_Fbhash_Utils_General {

  const HASH_SEPARATOR = '|';

  public static function fixmeDataHashedFilters() {
    return [];
  }

  public static function getHashedFilters($afformName) {
    $allFilters = CRM_Fbhash_Utils_General::fixmeDataHashedFilters();
    return $allFilters[$afformName] ?? [];
  }

  public static function hashValue($value) {
    $salt = CIVICRM_SITE_KEY;
    $saltedValue = $value . $salt;
    // $hashType = 'xxh64';
    $hashAlgo = 'sha256';
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

  public static function getAfformHashedUrl($afformName, array $filters = []) {
    $afform = \Civi\Api4\Afform::get()
      ->setCheckPermissions(FALSE)
      ->addWhere('name', '=', $afformName)
      ->setLimit(1)
      ->execute()
      ->first();
    $url = CRM_Utils_System::url($afform['server_route'], NULL, TRUE);
    if (!empty($filters)) {
      $filters = CRM_Fbhash_Utils_General::hashFilters($afformName, $filters);
      $url .= '#/?' . http_build_query($filters);
    }
    return $url;
  }

}
