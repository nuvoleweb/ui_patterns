<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\equals;
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

    foreach ($manager->getDefinitions() as $definition) {
      $pattern = $manager->getPattern($definition->id());
      assert($pattern->getBaseId(), equals($definition->id()));
    }
  }

}
