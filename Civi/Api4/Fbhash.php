<?php

namespace Civi\Api4;

/**
 * API pseudo-entity for the FormBuilder Hash extension.
 *
 * @package Civi\Api4
 */
class Fbhash extends Generic\AbstractEntity {

  /**
   * Every entity **must** implement `getFields`.
   *
   * This tells the action classes what input/output fields to expect,
   * and also populates the _API Explorer_.
   *
   * The `BasicGetFieldsAction` takes a callback function. We could have defined the function elsewhere
   * and passed a `callable` reference to it, but passing in an anonymous function works too.
   *
   * The callback function takes the `BasicGetFieldsAction` object as a parameter in case we need to access its properties.
   * Especially useful is the `getAction()` method as we may need to adjust the list of fields per action.
   *
   * Note that it's possible to bypass this function if an action class lists its own fields by declaring a `fields()` method.
   *
   * Read more about how to implement your own `GetFields` action:
   * @see \Civi\Api4\Generic\BasicGetFieldsAction
   *
   * @param bool $checkPermissions
   *
   * @return Generic\BasicGetFieldsAction
   */
  public static function getFields($checkPermissions = TRUE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function($getFieldsAction) {
      return [
        [
          'name' => 'afformName',
          'data_type' => 'String',
          'description' => 'Machine name of the relevant FormBuilder form.',
        ],
        [
          'name' => 'filters',
          'description' => "Array of filters to be (hashed and then) appended to the URL",
        ],
      ];
    }))->setCheckPermissions($checkPermissions);
  }

  /**
   * Unlike the other Basic action classes, `Replace` does not require any callback.
   *
   * This is because it calls `Get`, `Save` and `Delete` internally - those must be defined for an entity to implement `Replace`.
   *
   * Read more about the `Replace` action:
   * @inheritDoc
   * @see \Civi\Api4\Generic\BasicReplaceAction
   * @return Generic\BasicReplaceAction
   */
  public static function hashAfformUrl($checkPermissions = TRUE) {
    return (new Action\Fbhash\HashAfformUrl(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

}
