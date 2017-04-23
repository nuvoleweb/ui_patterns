<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\isNotEmpty;
use function bovigo\assert\predicate\hasKey;
use function bovigo\assert\predicate\equals;

/**
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternsSourceManager
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
    /** @var \Drupal\ui_patterns\UiPatternsSourceManager $service */
    $plugin_manager = \Drupal::service('plugin.manager.ui_patterns_source');

    $definitions = $plugin_manager->getDefinitions();
    assert($definitions, isNotEmpty()->and(hasKey('test_source')));

    $expected = [
      ['name' => 'field_1', 'label' => 'Field 1'],
      ['name' => 'field_2', 'label' => 'Field 2'],
      ['name' => 'field_3', 'label' => 'Field 3'],
      ['name' => 'field_4', 'label' => 'Field 4'],
      ['name' => 'field_5', 'label' => 'Field 5'],
    ];

    /** @var \Drupal\ui_patterns\Plugin\PatternSourceBase $plugin */
    $plugin = $plugin_manager->createInstance('test_source');
    foreach ($plugin->getSourceFields() as $key => $field) {
      assert($field->getFieldName(), equals($expected[$key]['name']));
      assert($field->getFieldLabel(), equals($expected[$key]['label']));
    }
  }

}
