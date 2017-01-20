<?php

namespace Drupal\ui_patterns\Tests\Unit;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\hasKey;
use function bovigo\assert\predicate\equals;
use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Component\Serialization\Yaml;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\ui_patterns\UiPatternsManager;

/**
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternsManager
 *
 * @group ui_patterns
 */
class UiPatternsManagerTest extends AbstractUiPatternsTest {

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
    $cache_backend = $this->getCacheBackendMock();
    $module_handler = $this->getModuleHandlerMock();
    $theme_handler = $this->getThemeHandlerMock();
    $theme_manager = $this->getThemeManagerMock();

    $plugin_manager = new UiPatternsManager($module_handler, $theme_handler, $theme_manager, $cache_backend);
    $plugin_manager->setYamlDiscovery($this->getYamlDiscoveryMock());
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
   * Test plugin validation.
   */
  public function testValidation() {
    $definitions = Yaml::decode(file_get_contents(dirname(__FILE__) . '/bad_definitions.ui_patterns.yml'));
    foreach ($definitions as $definition) {
      try {
        UiPatternsManager::validateDefinition($definition);
      }
      catch (PluginException $e) {
        assert($e->getMessage(), equals($definition['throws']));
      }
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

}
