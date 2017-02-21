<?php

namespace Drupal\Tests\ui_patterns\Kernel\Plugin\Deriver;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\hasKey;
use Drupal\Tests\ui_patterns\Kernel\AbstractUiPatternsTest;
use Drupal\ui_patterns\UiPatterns;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Plugin\Deriver\YamlDeriver
 *
 * @group ui_patterns
 */
class YamlDeriverTest extends AbstractUiPatternsTest {

  /**
   * Test get derivative definitions.
   *
   * @covers ::getDerivativeDefinitions
   */
  public function testGetDerivativeDefinitions() {
    foreach (UiPatterns::getManager()->getDefinitions() as $definition) {
      assert($definition, hasKey('id')
        ->and(hasKey('provider'))
        ->and(hasKey('base path'))
      );
    }
  }

}
