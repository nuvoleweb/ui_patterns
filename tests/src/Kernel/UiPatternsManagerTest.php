<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use Drupal\Tests\token\Kernel\KernelTestBase;
use function bovigo\assert\assert;
use function bovigo\assert\predicate\isNotEmpty;

/**
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternsManager
 *
 * @group ui_patterns
 */
class UiPatternsManagerTest extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'ui_patterns',
    'ui_patterns_test',
  ];

  /**
   * Test get definitions.
   *
   * @covers ::getDefinitions
   */
  public function testGetDefinitions() {
    /** @var \Drupal\ui_patterns\UiPatternsManager $manager */
    $manager = \Drupal::service('plugin.manager.ui_patterns');
    $definitions = $manager->getDefinitions();
    assert($definitions, isNotEmpty());
  }

}
