<?php

namespace Drupal\Tests\ui_patterns\Kernel\Plugin\Deriver;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\hasKey;
use Drupal\Tests\ui_patterns\Kernel\AbstractUiPatternsTest;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Plugin\Deriver\YamlDeriver
 *
 * @group ui_patterns
 */
class YamlDeriverTest extends AbstractUiPatternsTest {

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
   * Test get derivative definitions.
   *
   * @covers ::getDerivativeDefinitions
   */
  public function testGetDerivativeDefinitions() {
    /** @var \Drupal\ui_patterns\UiPatternsManager $manager */
    $manager = \Drupal::service('plugin.manager.ui_patterns');

    foreach ($manager->getDefinitions() as $definition) {
      assert($definition, hasKey('id')
        ->and(hasKey('provider'))
        ->and(hasKey('base path'))
      );
    }
  }

}
