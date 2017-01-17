<?php

namespace Drupal\ui_patterns\Tests\Unit;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\isNotEmpty;
use Drupal\ui_patterns\Plugin\UiPatternsSourceManager;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Plugin\UiPatternsSourceManager
 *
 * @group ui_patterns
 */
class UiPatternsSourceManagerTest extends AbstractUiPatternsTest {

  /**
   * Test processDefinition.
   *
   * @covers ::processDefinition
   */
  public function testProcessDefinition() {
    $cache_backend = $this->getCacheBackendMock();
    $module_handler = $this->getModuleHandlerMock();
    $path = $this->getExtensionsPath('ui_patterns_test') . '/src';
    $traversable = new \ArrayObject(['Drupal\\ui_patterns_test' => $path]);
    $plugin_manager = new UiPatternsSourceManager($traversable, $cache_backend, $module_handler);

    $definitions = $plugin_manager->getDefinitions();
    assert($definitions, isNotEmpty());
  }

}
