<?php

namespace Drupal\Tests\ui_patterns\Kernel\Plugin\UiPatterns\Pattern;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\isNotEmpty;
use Drupal\Tests\ui_patterns\Kernel\AbstractUiPatternsTest;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Plugin\UiPatterns\Pattern\YamlPattern
 *
 * @group ui_patterns
 */
class YamlPatternTest extends AbstractUiPatternsTest {

  /**
   * Test YamlPattern::getLibraries().
   *
   * @covers ::getLibraries
   */
  public function testGetLibraries() {
    $manager = $this->getPluginManager($this->getFixtureDefinitions());
    assert($manager, isNotEmpty());
  }

}
