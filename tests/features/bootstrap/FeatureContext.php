<?php

/**
 * @file
 */

use Behat\Gherkin\Node\TableNode;
use NuvoleWeb\Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\node\Entity\NodeType;

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
   * The list of node type before a scenario starts.
   *
   * @var array
   */
  private $defaultNodeTypes = [];

  /**
   * Assert that modules are enabled.
   *
   * @Then the following modules are enabled:
   */
  public function assertModulesEnabled(TableNode $table) {
    $rows = $table->getRows();
    foreach ($rows as $row) {
      if (!\Drupal::moduleHandler()->moduleExists($row[0])) {
        throw new \Exception("Module '{$row[0]}' should be enabled but it is not.");
      }
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

  /**
   * Remember the list of node type.
   *
   * @BeforeScenario @restoreContentTypes
   */
  public function rememberCurrentNodeTypes() {
    $this->defaultNodeTypes = NodeType::loadMultiple();
  }

  /**
   * Removes any node types created after the last list node type remembered.
   *
   * @AfterScenario @restoreContentTypes
   */
  public function restoreContentTypes() {
    # Update the list of node types
    # TODO: \Drupal::entityTypeManager()->getStorage('node')->resetCache()
    # TODO: doesn't work.
    drupal_flush_all_caches();

    foreach (NodeType::loadMultiple() as $machine_name => $content_type) {
      if (!in_array($content_type, $this->defaultNodeTypes)) {
        # Delete all nodes associated to the content type
        $nodes = \Drupal::entityTypeManager()
          ->getStorage('node')
          ->loadByProperties(array('type' => $machine_name));

        foreach ($nodes as $node) {
          $node->delete();
        }

        # Delete the content type
        NodeType::load($machine_name)->delete();
      }
    }

    $this->defaultNodeTypes = [];
  }

}
