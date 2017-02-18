<?php

namespace Drupal\ui_patterns\Tests\Unit;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\equals;
use function bovigo\assert\predicate\hasKey;
use Drupal\Component\Serialization\Yaml;
use Drupal\ui_patterns\Plugin\Deriver\YamlDeriver;

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
   *
   * @dataProvider definitionFiles
   */
  public function testGetDerivativeDefinitions($provider, $file) {
    $module_handler = $this->getModuleHandlerMock();
    $theme_handler = $this->getThemeHandlerMock();

    $deriver = new YamlDeriver('yaml_pattern', $module_handler, $theme_handler);
    $actual = $deriver->getDerivativeDefinitions([]);

    $expected = Yaml::decode(file_get_contents($file));
    foreach ($expected as $id => $definition) {
      assert($actual, hasKey($id));
      assert($actual[$id], hasKey('id')
        ->and(hasKey('provider'))
        ->and(hasKey('base path'))
      );
      assert($actual[$id]['provider'], equals($provider));

      foreach ($definition as $key => $value) {
        assert($actual[$id][$key], equals($value));
      }
    }
  }

  /**
   * Get definition files.
   *
   * @return array
   *    Definition data provider.
   */
  public function definitionFiles() {
    return [
      ['module', $this->getExtensionsPath('module') . '/module.ui_patterns.yml'],
      ['theme', $this->getExtensionsPath('theme') . '/theme.ui_patterns.yml'],
      ['theme', $this->getExtensionsPath('theme') . '/pattern/pattern.ui_patterns.yml'],
      ['base_theme', $this->getExtensionsPath('base_theme') . '/base_theme.ui_patterns.yml'],
    ];
  }

}
