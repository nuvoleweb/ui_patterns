<?php

namespace Drupal\ui_patterns_ds\Plugin\UiPatterns\Source;

/**
 * Defines Display Suite fields source plugin.
 *
 * @UiPatternsSource(
 *   id = "ds_field",
 *   label = @Translation("Display Suite"),
 *   provider = "ds",
 *   tags = {
 *     "entity_display"
 *   }
 * )
 */
class DsFieldSource extends AbstractDsFieldSource {

  /**
   * {@inheritdoc}
   */
  public function getSourceFields() {
    $sources = [];
    $fields = $this->dsManager->getDefinitions();

    foreach ($fields as $field) {
      if (!$this->getContextProperty('limit')) {
        $sources[] = $this->getSourceField($field['id'], $field['title']);
      }
      elseif (in_array($field['id'], $this->getContextProperty('limit'))) {
        $sources[] = $this->getSourceField($field['id'], $field['title']);
      }
    }
    return $sources;
  }

}
