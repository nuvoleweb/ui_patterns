<?php

namespace Drupal\ui_patterns\Tests\Behat;

use Behat\Gherkin\Node\TableNode;
use NuvoleWeb\Drupal\DrupalExtension\Context\RawDrupalContext;
use function bovigo\assert\assert;
use function bovigo\assert\predicate\isTrue;

/**
 * Class FeatureContext.
 *
 * @package Drupal\ui_patterns\Tests\Behat
 */
class FeatureContext extends RawDrupalContext {

  /**
   * Assert that modules are enabled.
   *
   * @Then the following modules are enabled:
   */
  public function assertModulesEnabled(TableNode $table) {
    $rows = $table->getRows();
    foreach ($rows as $row) {
      assert(\Drupal::moduleHandler()->moduleExists($row[0]), isTrue(), "Module '{$row[0]}' should be enabled but it is not.");
    }
  }

}
