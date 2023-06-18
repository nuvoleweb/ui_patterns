<?php

namespace Drupal\Tests\ui_patterns\Kernel;

/**
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternsSourceManager
 *
 * @group ui_patterns
 */
class UiPatternsSourceManagerTest extends AbstractUiPatternsTest {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'ui_patterns',
    'ui_patterns_field_source_test',
  ];

  /**
   * Test processDefinition.
   *
   * @covers ::processDefinition
   */
  public function testProcessDefinition() {
    /** @var \Drupal\ui_patterns\UiPatternsSourceManager $service */
    $plugin_manager = \Drupal::service('plugin.manager.ui_patterns_source');

    $definitions = $plugin_manager->getDefinitions();
    $this->assertNotEmpty($definitions);
    $this->assertArrayHasKey('test_source', $definitions);

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
      $this->assertEquals($expected[$key]['name'], $field->getFieldName());
      $this->assertEquals($expected[$key]['label'], $field->getFieldLabel());
    }
  }

}
