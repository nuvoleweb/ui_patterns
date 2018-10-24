<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use Drupal\ui_patterns\UiPatterns;

/**
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternsManager
 *
 * @group ui_patterns
 */
class UiPatternsManagerTest extends AbstractUiPatternsTest {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'ui_patterns',
  ];

  /**
   * Test UiPatternsManager::getPatternDefinition.
   *
   * @covers ::getPatternDefinition
   */
  public function testGetPattern() {
    $manager = UiPatterns::getManager();
    $definitions = $manager->getDefinitions();

    foreach ($manager->getPatterns() as $pattern) {
      $this->assertEquals($definitions[$pattern->getPluginId()]->id(), $pattern->getBaseId());
    }
  }

}
