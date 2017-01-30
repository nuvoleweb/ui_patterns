<?php

namespace Drupal\module\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\Plugin\UiPatternsSourceBase;

/**
 * Test two.
 *
 * @UiPatternsSource(
 *   id = "test_two",
 *   label = @Translation("Test two"),
 *   provider = "module",
 *   tags = {
 *     "test"
 *   }
 * )
 */
class TestTwoFieldSource extends UiPatternsSourceBase {

  /**
   * {@inheritdoc}
   */
  public function getSourceFields() {
    return [
      $this->getSourceField('test_two', 'Test two'),
    ];
  }

}
