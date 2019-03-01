<?php

namespace Drupal\ui_patterns_field_source_test\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\Plugin\PatternSourceBase;

/**
 * Defines Fields API pattern source plugin.
 *
 * @UiPatternsSource(
 *   id = "test_source",
 *   label = @Translation("Test source"),
 *   provider = "ui_patterns_field_source_test",
 *   tags = {
 *     "test"
 *   }
 * )
 */
class TestSource extends PatternSourceBase {

  /**
   * {@inheritdoc}
   */
  public function getSourceFields() {
    return [
      $this->getSourceField('field_1', 'Field 1'),
      $this->getSourceField('field_2', 'Field 2'),
      $this->getSourceField('field_3', 'Field 3'),
      $this->getSourceField('field_4', 'Field 4'),
      $this->getSourceField('field_5', 'Field 5'),
    ];
  }

}
