<?php

/**
 * @file
 */

use Behat\Gherkin\Node\TableNode;
use NuvoleWeb\Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Class FeatureContext.
 *
 * @package Drupal\ui_patterns\Tests\Behat
 */
class FeatureContext extends RawDrupalContext {

  /**
   * Store original values of 'system.performance' configuration.
   *
   * @var array
   */
  private $systemPerformance = [];

  /**
   * Assert that modules are enabled.
   *
   * @Then the following modules are enabled:
   */
  public function assertModulesEnabled(TableNode $table) {
    $rows = $table->getRows();
    foreach ($rows as $row) {
      expect(\Drupal::moduleHandler()->moduleExists($row[0]))->to->be->true("Module '{$row[0]}' should be enabled but it is not.");
    }
  }

  /**
   * Assert that modules are enabled.
   *
   * @BeforeScenario @disableCompression
   */
  public function disableCompression() {
    $this->systemPerformance = \Drupal::config('system.performance')->get();
    \Drupal::configFactory()->getEditable('system.performance')->setData([
      'css' => ['preprocess' => FALSE],
      'js' => ['preprocess' => FALSE],
    ])->save();
  }

  /**
   * Restore performance settings.
   *
   * @AfterScenario @disableCompression
   */
  public function restorePerformanceSettings() {
    if (!empty($this->systemPerformance)) {
      \Drupal::configFactory()->getEditable('system.performance')->setData($this->systemPerformance)->save();
      $this->systemPerformance = [];
    }
  }

}
