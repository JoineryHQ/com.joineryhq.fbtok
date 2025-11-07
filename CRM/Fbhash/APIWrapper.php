<?php

use CRM_Fbhash_ExtensionUtil as E;

/**
 * Description of CRM_Stepw_APIWrapper
 *
 * @author as
 */
class CRM_Fbhash_APIWrapper {

  /**
   * API wrapper for 'prepare' events; delegates to private static methods in this class.
   *
   * @param Civi\API\Event\PrepareEvent $event
   */
  public static function PREPARE(Civi\API\Event\PrepareEvent $event) {
    // Pass event to the PREPARE handler for this api request, if one exists in this class.
    $requestSignature = $event->getApiRequestSig();
    $methodName = 'PREPARE_' . str_replace('.', '_', $requestSignature);
    if (is_callable([self::class, $methodName])) {
      call_user_func_array([self::class, $methodName], [$event]);
    }
  }

  /**
   * API wrapper for 'respond' events; delegates to private static methods in this class.
   *
   * @param Civi\API\Event\RespondEvent $event
   */
  public static function RESPOND(Civi\API\Event\RespondEvent $event) {
    // Pass event to the RESPOND handler for this api request, if one exists in this class.
    $requestSignature = $event->getApiRequestSig();
    $methodName = 'RESPOND_' . str_replace('.', '_', $requestSignature);
    if (is_callable([self::class, $methodName])) {
      call_user_func_array([self::class, $methodName], [$event]);
    }
  }

  private static function PREPARE_4_searchdisplay_run($event) {
    $request = $event->getApiRequest();
    $params = $request->getParams();
    $filters = $params['filters'];

    $filters = CRM_Fbhash_Utils_General::dehashFilters($params['afform'], $filters);

    $request->setFilters($filters);
  }

}
