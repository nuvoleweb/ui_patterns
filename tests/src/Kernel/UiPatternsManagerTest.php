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
   * Test UiPatternsManager::getPatternDefinition.
   *
   * @covers ::getPatternDefinition
   */
  public function testGetPattern() {
    $manager = UiPatterns::getManager();
    $definitions = $manager->getDefinitions();

    foreach ($manager->getPatterns() as $pattern) {
      expect($pattern->getBaseId())->to->equal($definitions[$pattern->getPluginId()]->id());
    }
  }

}
