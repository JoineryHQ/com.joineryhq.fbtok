<?php

namespace Civi\Api4\Action\Fbtok;

use Civi\Api4\Generic\Result;

/**
 * For a given FormBuilder form, and a given set of filters (which would normally
 * be passed in the clear as URL query parameters), tokenize those filters and append
 * them to a valid URL for viewing that form.
 *
 * @package Civi\Api4\Action\Fbtok
 */
class TokenizeAfformUrl extends \Civi\Api4\Generic\AbstractAction {

  /**
   * Prefix to add to every random value.
   *
   * We define this parameter just by declaring this variable. It will appear in the _API Explorer_,
   * and a getter/setter are magically provided: `$this->setPrefix()` and `$this->getPrefix()`.
   *
   * Declaring this variable with a value (in this case the empty string `''`), sets the default.
   *
   * @var array
   */
  protected $filters = [];

  /**
   * Number of rows to generate.
   *
   * We can make a parameter required with this annotation:
   * @required
   *
   * We can also require a certain type of input with this annotation:
   * @var string
   */
  protected $afformName;

  /**
   * Every action must define a _run function to perform the work and place results in the Result object.
   *
   * When using the set of Basic actions, they define _run for you and you just need to provide a getter/setter function.
   *
   * @param Result $result
   * 
   * @throws CRM_Core_Exception if afform not found.
   */
  public function _run(Result $result) {
    $afform = \Civi\Api4\Afform::get()
      ->setCheckPermissions(FALSE)
      ->addWhere('name', '=', $this->afformName)
      ->setLimit(1)
      ->execute()
      ->first();
    if (empty($afform)) {
      throw new \CRM_Core_Exception('Afform not found by name: '. $this->afformName);
    }
    $url = \CRM_Utils_System::url($afform['server_route'], NULL, TRUE, NULL, FALSE, ($afform['is_public'] ?? FALSE));
    if (!empty($this->filters)) {
      $filters = \CRM_Fbtok_Utils_General::tokenizeFilters($this->afformName, $this->filters);
      $url .= '#/?' . http_build_query($filters);
    }
    $result[] = [
      'url' => $url,
    ];
    return;
  }

  /**
   * Declare ad-hoc field list for this action.
   *
   * Some actions return entirely different data to the entity's "regular" fields.
   *
   * This is a convenient alternative to adding special logic to our GetFields function to handle this action.
   *
   * @return array
   */
  public static function fields() {
    return [
      ['name' => 'row', 'data_type' => 'Integer'],
      ['name' => 'random'],
    ];
  }

}
