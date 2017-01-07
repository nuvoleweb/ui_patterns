<?php

namespace Drupal\ui_patterns\Tests\Unit;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\hasKey;
use function bovigo\assert\predicate\equals;
use Drupal\Component\FileCache\FileCacheFactory;
use PHPUnit\Framework\TestCase;
use Drupal\ui_patterns\UiPatternsManager;

/**
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternsManager
 *
 * @group ui_patterns
 */
class UiPatternsManagerTest extends TestCase {

  /**
   * Test processDefinition.
   *
   * @covers ::processDefinition
   */
  public function testProcessDefinition() {
    $cache_backend = $this->createMock('Drupal\Core\Cache\CacheBackendInterface');

    $theme_handler = $this->getMockBuilder('Drupal\Core\Extension\ThemeHandlerInterface')
      ->disableOriginalConstructor()
      ->getMock();
    $theme_handler->method('getThemeDirectories')->willReturn([]);
    $theme_handler->method('themeExists')->willReturn(TRUE);

    $theme_manager = $this->getMockBuilder('Drupal\Core\Theme\ThemeManager')
      ->disableOriginalConstructor()
      ->getMock();

    $extension = $this->getMockBuilder('Drupal\Core\Extension\Extension')
      ->disableOriginalConstructor()
      ->getMock();
    $extension->method('getPath')->willReturn($this->getTestModulePath());

    $module_handler = $this->createMock('Drupal\Core\Extension\ModuleHandlerInterface');
    $module_handler->method('getModuleDirectories')->willReturn([
      'ui_patterns_test' => $this->getTestModulePath(),
    ]);
    $module_handler->method('getModule')->willReturn($extension);
    $module_handler->method('moduleExists')->willReturn(TRUE);

    FileCacheFactory::setPrefix('something');
    $plugin_manager = new UiPatternsManager($module_handler, $theme_handler, $theme_manager, $cache_backend);
    $definitions = $plugin_manager->getDefinitions();

    $id = 'metadata';
    assert($definitions, hasKey($id));
    assert($definitions[$id], hasKey('label')
      ->and(hasKey('description'))
      ->and(hasKey('fields'))
      ->and(hasKey('libraries'))
      ->and(hasKey('theme hook'))
      ->and(hasKey('theme variables')));
    assert($definitions[$id]['theme hook'], equals("pattern__{$id}"));
    assert($definitions[$id]['libraries'], equals([
      'module/library1',
      'module/library2',
    ]));

    $variables = array_keys($definitions[$id]['fields']);
    $variables[] = 'attributes';
    foreach ($variables as $variable) {
      assert($definitions[$id]['theme variables'], hasKey($variable));
    }

    $definition = $plugin_manager->getDefinitionByThemeHook('overridden_pattern_hook');
    assert($definition, hasKey('id'));
    assert($definition['id'], equals('overridden_pattern'));
  }

  /**
   * Get full path of test module.
   *
   * @return string
   *    Full module path.
   */
  protected function getTestModulePath() {
    return realpath(dirname(__FILE__) . '/../../../tests/target/custom/ui_patterns_test');
  }

}
