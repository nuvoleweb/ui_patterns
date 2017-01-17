<?php

namespace Drupal\ui_patterns\Tests\Unit;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\isNotEmpty;
use Drupal\Component\FileCache\FileCacheFactory;
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
    $cache_backend = $this->createMock('Drupal\Core\Cache\CacheBackendInterface');

    $extension = $this->getMockBuilder('Drupal\Core\Extension\Extension')
      ->disableOriginalConstructor()
      ->getMock();
    $extension->method('getPath')->willReturn($this->getExtensionsPath('ui_patterns_test'));

    $module_handler = $this->createMock('Drupal\Core\Extension\ModuleHandlerInterface');
    $module_handler->method('getModuleDirectories')->willReturn([
      'ui_patterns_test' => $this->getExtensionsPath('ui_patterns_test'),
    ]);
    $module_handler->method('getModule')->willReturn($extension);
    $module_handler->method('moduleExists')->willReturn(TRUE);

    $traversable = new \ArrayObject([
      'Drupal\\ui_patterns_test' => $this->getExtensionsPath('ui_patterns_test') . '/src',
    ]);
    FileCacheFactory::setPrefix('something');
    $plugin_manager = new UiPatternsSourceManager($traversable, $cache_backend, $module_handler);
    $definitions = $plugin_manager->getDefinitions();

    assert($definitions, isNotEmpty());
  }

}
