<?php

namespace Drupal\ui_patterns\Tests\Unit;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\equals;
use Drupal\Component\Serialization\Yaml;
use Drupal\ui_patterns\Element\PatternPreview;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Element\PatternPreview
 *
 * @group ui_patterns
 */
class PatternPreviewTest extends AbstractUiPatternsTest {

  /**
   * Test getPreviewMarkup.
   *
   * @covers ::getPreviewMarkup
   */
  public function testPreviewMarkup() {
    $assertions = Yaml::decode(file_get_contents(dirname(__FILE__) . '/fixtures/preview_markup.yml'));
    foreach ($assertions as $assertion) {
      $result = PatternPreview::getPreviewMarkup($assertion['actual']);
      assert($assertion['expected'], equals($result));
    }
  }

}
