<?php

use CRM_Fbtok_ExtensionUtil as E;

/**
 * General-purpose utilities for stepw extension.
 *
 */
class CRM_Fbtok_Utils_General {

  const VALUE_SEPARATOR = '|';

  public static function setSetting($settingName, $value) {
    $settingKey = E::LONG_NAME . '.' . $settingName;
    \Civi::settings()->set($settingKey, $value);
  }

  public static function getSetting($settingName, $default = NULL) {
    if (!isset(Civi::$statics[__CLASS__][$settingName])) {
      $settingKey = E::LONG_NAME . '.' . $settingName;
      Civi::$statics[__CLASS__][$settingName] = \Civi::settings()->get($settingKey);
    }
    return (Civi::$statics[__CLASS__][$settingName] ?? $default);
  }

  public static function getHmacKey() {
    $hmacKey = self::getSetting('hmacKey');
    if (empty($hmacKey)) {
      $hmacKey = bin2hex(random_bytes(32));
      self::setSetting('hmacKey', $hmacKey);
    }
    return $hmacKey;
  }

  public static function getTokenizedFilters($afformName) {
    $allFilters = self::getSetting('tokenizedFilters');
    return ($allFilters[$afformName] ?? []);
  }

  public static function tokenizeValue($value) {
    $entropyBytes = self::getSetting('tokenEntropyBytes', 8);
    $secret = self::getHmacKey();
    $fullHmac = hash_hmac('sha256', (string) $value, $secret, TRUE);
    // Truncate to bytes (e.g. 8 bytes = 64 bits)
    $truncatedHmac = substr($fullHmac, 0, $entropyBytes);
    // Convert binary to url-safe text.
    $b64 = base64_encode($truncatedHmac);
    // Replace url special chars with suitable replacements.
    $urlSafeB64 = strtr($b64, '+/', '-_');
    // remove trailing '=' padding
    $token = rtrim($urlSafeB64, '=');

    $ret = $token . CRM_Fbtok_Utils_General::VALUE_SEPARATOR . $value;
    return $ret;
  }

  public static function getPlainValue($tokenizedValue) {
    list($trash, $value) = explode(CRM_Fbtok_Utils_General::VALUE_SEPARATOR, $tokenizedValue);
    if ($tokenizedValue == CRM_Fbtok_Utils_General::tokenizeValue($value)) {
      return $value;
    }
    else {
      return FALSE;
    }
  }

  public static function detokenizeFilters($afformName, $filters) {
    $ret = $filters;
    foreach (CRM_Fbtok_Utils_General::getTokenizedFilters($afformName) as $filterKey) {
      // fixme: I once thought this was a good idea, but why?
      // if (!array_key_exists($filterKey, $filters)) {
      //   continue;
      // }
      if ($plainValue = CRM_Fbtok_Utils_General::getPlainValue($filters[$filterKey])) {
        $ret[$filterKey] = $plainValue;
      }
      else {
        $ret[$filterKey] = -1;
      }
    }
    return $ret;
  }

  public static function tokenizeFilters($afformName, $filters) {
    $ret = $filters;
    foreach (CRM_Fbtok_Utils_General::getTokenizedFilters($afformName) as $filterKey) {
      if (!array_key_exists($filterKey, $filters)) {
        continue;
      }
      $ret[$filterKey] = CRM_Fbtok_Utils_General::tokenizeValue($filters[$filterKey]);
    }
    return $ret;
  }

}
