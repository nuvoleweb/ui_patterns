<?php

namespace Drupal\Tests\ui_patterns\Unit\Element;

use Drupal\Component\Serialization\Yaml;
use Drupal\Tests\ui_patterns\Unit\AbstractUiPatternsTest;
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
    $assertions = Yaml::decode(file_get_contents($this->getFixturePath() . '/preview_markup.yml'));
    foreach ($assertions as $assertion) {
      $result = PatternPreview::getPreviewMarkup($assertion['actual']);
      expect($assertion['expected'])->to->equal($result);
    }
  }

}
