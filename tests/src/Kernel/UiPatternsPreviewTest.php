<?php

namespace Drupal\Tests\ui_patterns\Unit\Element;

use Drupal\Tests\ui_patterns\Kernel\AbstractUiPatternsTest;
use Drupal\Tests\ui_patterns\Traits\RenderTrait;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Element\PatternPreview
 *
 * @group ui_patterns
 */
class UiPatternsPreviewTest extends AbstractUiPatternsTest {

  use RenderTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'ui_patterns',
    'ui_patterns_library',
    'ui_patterns_render_test',
  ];

  /**
   * Test processContext.
   *
   * @dataProvider processContextDataProvider
   *
   * @covers ::processContext
   */
  public function testProcessContext($render, $expected) {
    $this->renderRoot($render);
    $this->assertEquals($expected, $render["#context"]->getType());
  }

  /**
   * Data provider for Process Context tests.
   *
   * The actual data is read from fixtures stored in a YAML configuration.
   *
   * @return array
   *   A set of dump data for testing.
   */
  public function processContextDataProvider() {
    return $this->getFixtureContent('preview_process.yml');
  }

}
