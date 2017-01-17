<?php

namespace Drupal\ui_patterns\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\Plugin\UiPatternsSourceBase;

/**
 * Defines Fields API pattern source plugin.
 *
 * @UiPatternsSource(
 *   id = "field_source",
 *   label = @Translation("Field source"),
 *   provider = "field",
 *   tags = {
 *     "entity_display"
 *   }
 * )
 */
class FieldSource extends UiPatternsSourceBase {

  /**
   * {@inheritdoc}
   */
  public function getSourceFields() {
    return [];
  }

}
