<?php

namespace Drupal\Tests\ui_patterns\Kernel;

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
    expect($definitions)
      ->to->not->be->empty()
      ->and->to->have->keys(['test_source']);

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
      expect($field->getFieldName())->to->equal($expected[$key]['name']);
      expect($field->getFieldLabel())->to->equal($expected[$key]['label']);
    }
  }

}
