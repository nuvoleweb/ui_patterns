<?php

namespace Drupal\ui_patterns\Tests\Behat;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\isNotEmpty;
use NuvoleWeb\Drupal\DrupalExtension\Context\RawMinkContext;

/**
 * Class MinkContext.
 *
 * @package Drupal\ui_patterns\Tests\Behat
 */
class MinkContext extends RawMinkContext {

  /**
   * Set a draggable table's row parent.
   *
   * @Given I move the :target row under the :parent row
   */
  public function setParentRow($target, $parent) {
    $driver = $this->getSession()->getDriver();

    $parent_row = "jQuery('.group-label:contains($parent)').closest('tr')";
    assert($driver->evaluateScript($parent_row), isNotEmpty(), "Parent row '$parent' not found.");

    $target_row = "jQuery('td:contains($target)').closest('tr').find('.field-parent')";
    assert($driver->evaluateScript($target_row), isNotEmpty(), "Target row '$target' not found.");

    $parent_id = $driver->evaluateScript("$parent_row.attr('id').split('-').join('_');");
    assert($parent_id, isNotEmpty(), "Parent row ID for '$parent' not found.");

    $driver->evaluateScript("$target_row.val('$parent_id');");
  }

}
