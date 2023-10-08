<?php

namespace Drupal\ui_patterns_field_formatters\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\Plugin\PatternSourceBase;

/**
 * Defines Field meta properties source plugin.
 *
 * @UiPatternsSource(
 *   id = "field_meta_properties",
 *   label = @Translation("Field meta properties"),
 *   tags = {
 *     "field_properties"
 *   }
 * )
 */
class FieldMetaPropertiesSource extends PatternSourceBase {

  /**
   * {@inheritdoc}
   */
  public function getSourceFields() {
    $sources = [];
    $sources[] = $this->getSourceField('_label', 'Label');
    $sources[] = $this->getSourceField('_formatted', 'Formatted values');
    return $sources;
  }

}
