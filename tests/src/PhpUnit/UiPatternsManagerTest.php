<?php

namespace Drupal\ui_patterns\Tests\Unit;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\hasKey;
use function bovigo\assert\predicate\equals;
use Drupal\Component\Serialization\Yaml;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\ui_patterns\Exception\PatternDefinitionException;
use Drupal\ui_patterns\UiPatternsManager;
use Drupal\ui_patterns\UiPatternsValidation;

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
    $loader = $this->getLoaderMock();
    $validation = $this->getValidationMock();

    $plugin_manager = new UiPatternsManager('', $module_handler, $theme_handler, $loader, $validation, $cache_backend);
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
    $validation = new UiPatternsValidation();
    $definitions = Yaml::decode(file_get_contents(dirname(__FILE__) . '/fixtures/validation.yml'));
    foreach ($definitions as $definition) {
      try {
        $validation->validate($definition);
      }
      catch (PatternDefinitionException $e) {
        assert($e->getMessage(), equals($definition['throws']));
      }
    }
  }

  /**
   * Test hookLibraryInfoBuild.
   *
   * @covers ::hookLibraryInfoBuild
   */
  public function testHookLibraryInfoBuild() {
    $items = Yaml::decode(file_get_contents(dirname(__FILE__) . '/fixtures/libraries.yml'));

    foreach ($items as $item) {
      $manager = $this->createPartialMock(UiPatternsManager::class, ['getDefinitions']);
      $manager->method('getDefinitions')->willReturn([$item['actual']]);

      /** @var \Drupal\ui_patterns\UiPatternsManager $manager */
      $libraries = $manager->hookLibraryInfoBuild();
      assert($libraries, equals($item['expected']));
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
      $this->getExtensionsPath('theme') . '/theme.ui_patterns.yml',
      $this->getExtensionsPath('module') . '/module.ui_patterns.yml',
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
