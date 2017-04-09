<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\isTrue;
use function bovigo\assert\predicate\equals;
use Drupal\Component\Serialization\Yaml;
use Drupal\ui_patterns\UiPatternBase;

/**
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternBase
 *
 * @group ui_patterns
 */
class UiPatternBaseTest extends AbstractUiPatternsTest {

  /**
   * Test getters.
   *
   * @covers ::getId
   * @covers ::getLabel
   * @covers ::hasField
   * @covers ::getField
   * @covers ::getFieldType
   * @covers ::getFieldLabel
   * @covers ::getFieldPreview
   * @covers ::hasCustomThemeHook
   * @covers ::getThemeHook
   * @covers ::getFields
   * @covers ::getFieldsAsOptions
   */
  public function testGetters() {
    $definitions = $this->getFixtureDefinitions();

    foreach ($definitions as $definition) {
      /** @var \Drupal\ui_patterns\UiPatternBase $pattern */
      $pattern = $this->getUiPatternBaseMock($definition);

      assert($pattern->getId(), equals($definition['id']));
      assert($pattern->getLabel(), equals($definition['label']));
      assert($pattern->getFields(), equals($definition['fields']));

      foreach ($pattern->getFieldsAsOptions() as $key => $value) {
        assert($pattern->hasField($key), isTrue());
        assert($pattern->getFieldLabel($key), equals($value));
      }

      foreach ($pattern->getFields() as $name => $field) {
        assert($pattern->getField($name), equals($definition['fields'][$name]));
        assert($pattern->getFieldType($name), equals($definition['fields'][$name]['type']));
        assert($pattern->getFieldLabel($name), equals($definition['fields'][$name]['label']));
        assert($pattern->getFieldPreview($name), equals($definition['fields'][$name]['preview']));
      }
    }
  }

  /**
   * Test hookLibraryInfoBuild.
   *
   * @covers ::hookLibraryInfoBuild
   */
  public function testHookLibraryInfoBuild() {
    $items = Yaml::decode(file_get_contents(dirname(__FILE__) . '/../fixtures/libraries.yml'));

    foreach ($items as $item) {
      $pattern = $this->getUiPatternBaseMock($item['actual']);

      /** @var \Drupal\ui_patterns\UiPatternBase $pattern */
      $libraries = $pattern->getLibraryDefinitions();
      assert($libraries, equals($item['expected']));
    }
  }

  /**
   * Get UiPatternBase mock.
   *
   * @param array $configuration
   *    Plugin configuration.
   * @param array $methods
   *    List of methods to mock.
   *
   * @return \PHPUnit_Framework_MockObject_MockObject
   *    Mock object.
   */
  protected function getUiPatternBaseMock(array $configuration = [], array $methods = []) {
    $root = \Drupal::service('app.root');
    $typed_data_manager = \Drupal::service('typed_data_manager');
    $arguments = [[], 'plugin_id', $configuration, $root, $typed_data_manager];
    return $this->getMockForAbstractClass(UiPatternBase::class, $arguments, '', TRUE, TRUE, TRUE, $methods);
  }

}
