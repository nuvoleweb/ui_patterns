<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Plugin\UiPatterns\Source\ExtraFieldSource
 *
 * @group ui_patterns
 */
class UiPatternsExtraFieldSourceTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'entity_test',
    'ui_patterns',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() : void {
    parent::setUp();

    $this->installEntitySchema('entity_test');
  }

  /**
   * Test getSourceFields.
   *
   * @covers ::getSourceFields
   */
  public function testGetSourceFields() {
    /** @var \Drupal\ui_patterns\UiPatternsSourceManager $manager */
    $manager = \Drupal::service('plugin.manager.ui_patterns_source');

    /** @var \Drupal\ui_patterns\Plugin\UiPatterns\Source\ExtraFieldSource $source */
    $fields = $manager->getFieldsByTag('entity_display', [
      'entity_type' => 'entity_test',
      'entity_bundle' => 'bundle_with_extra_fields',
    ]);

    $this->assertArrayHasKey('extra_fields:display_extra_field', $fields);
    $this->assertArrayHasKey('extra_fields:display_extra_field_hidden', $fields);
  }

}
