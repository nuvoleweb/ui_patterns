<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\isTrue;
use function bovigo\assert\predicate\equals;
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
   * @covers ::hasField($nam
   * @covers ::getField($nam
   * @covers ::getFieldType($nam
   * @covers ::getFieldLabel($nam
   * @covers ::getFieldPreview($nam
   * @covers ::hasCustomThemeHook
   * @covers ::getThemeHook
   * @covers ::getFields
   * @covers ::getFieldsAsOptions
   */
  public function testGetters() {
    $definitions = $this->getFixtureDefinitions();

    foreach ($definitions as $definition) {
      /** @var \Drupal\ui_patterns\UiPatternBase $pattern */
      $arguments = [[], 'plugin_id', $definition];
      $pattern = $this->getMockForAbstractClass(UiPatternBase::class, $arguments);

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

}
