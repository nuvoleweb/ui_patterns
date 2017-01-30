<?php

namespace Drupal\module\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\Plugin\UiPatternsSourceBase;

/**
 * Test one.
 *
 * @UiPatternsSource(
 *   id = "test_one",
 *   label = @Translation("Test one"),
 *   provider = "module",
 *   tags = {
 *     "test"
 *   }
 * )
 */
class TestOneFieldSource extends UiPatternsSourceBase {

  /**
   * {@inheritdoc}
   */
  public function getSourceFields() {
    return [
      $this->getSourceField('test_one', 'Test one'),
    ];
  }

}
