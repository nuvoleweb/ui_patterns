<?php

namespace Drupal\ui_patterns\Tests\Unit;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\hasKey;
use function bovigo\assert\predicate\equals;
use Drupal\Component\FileCache\FileCacheFactory;
use Drupal\Component\Serialization\Yaml;
use Drupal\Core\StringTranslation\TranslatableMarkup;
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
   * @param string $id
   *    Pattern ID.
   * @param array $expected
   *    Expected pattern definition.
   *
   * @covers ::processDefinition
   *
   * @dataProvider definitionsProvider
   */
  public function testProcessDefinition($id, array $expected) {
    $cache_backend = $this->createMock('Drupal\Core\Cache\CacheBackendInterface');

    $theme_handler = $this->getMockBuilder('Drupal\Core\Extension\ThemeHandlerInterface')
      ->disableOriginalConstructor()
      ->getMock();
    $theme_handler->method('getThemeDirectories')->willReturn([
      'ui_patterns_test_theme' => $this->getExtensionsPath('ui_patterns_test_theme'),
    ]);
    $theme_handler->method('themeExists')->willReturn(TRUE);

    $theme_manager = $this->getMockBuilder('Drupal\Core\Theme\ThemeManager')
      ->disableOriginalConstructor()
      ->getMock();

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

    FileCacheFactory::setPrefix('something');
    $plugin_manager = new UiPatternsManager($module_handler, $theme_handler, $theme_manager, $cache_backend);
    $definitions = $plugin_manager->getDefinitions();

    assert($definitions, hasKey($id));
    assert($definitions[$id], hasKey('label')
      ->and(hasKey('description'))
      ->and(hasKey('fields'))
      ->and(hasKey('libraries'))
      ->and(hasKey('theme hook'))
      ->and(hasKey('theme variables')));

    $properties = [
      'label',
      'description',
      'fields',
    ];
    foreach ($properties as $property) {
      if ($definitions[$id][$property] instanceof TranslatableMarkup) {
        $definitions[$id][$property] = $definitions[$id][$property]->getUntranslatedString();
      }
      assert($definitions[$id][$property], equals($expected[$property]));
    }

    $properties = [
      'theme hook',
      'libraries',
    ];
    foreach ($properties as $property) {
      if (isset($expected[$property])) {
        assert($definitions[$id][$property], equals($expected[$property]));
      }
    }

    $variables = array_keys($definitions[$id]['fields']);
    $variables[] = 'attributes';
    foreach ($variables as $variable) {
      assert($definitions[$id]['theme variables'], hasKey($variable));
    }
  }

  /**
   * Pattern definitions data provider.
   *
   * @return array
   *    Definition arrays.
   */
  public function definitionsProvider() {
    $data = [];
    $files = [
      $this->getExtensionsPath('ui_patterns_test') . '/ui_patterns_test.ui_patterns.yml',
      $this->getExtensionsPath('ui_patterns_test_theme') . '/ui_patterns_test_theme.ui_patterns.yml',
    ];

    foreach ($files as $file) {
      $definitions = Yaml::decode(file_get_contents($file));
      foreach ($definitions as $name => $definition) {
        $data[] = [$name, $definition];
      }
    }

    return $data;
  }

  /**
   * Get full test extension path.
   *
   * @param string $name
   *    Test extension name.
   *
   * @return string
   *    Full test extension path.
   */
  protected function getExtensionsPath($name) {
    return realpath(dirname(__FILE__) . '/../../../tests/target/custom/' . $name);
  }

}
