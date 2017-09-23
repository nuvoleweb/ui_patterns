<?php

namespace Drupal\ui_patterns_ds\Plugin\UiPatterns\Source;

/**
 * Defines Display Suite dynamic fields source plugin.
 *
 * @UiPatternsSource(
 *   id = "ds_dynamic_field",
 *   label = @Translation("Display Suite dynamic field"),
 *   provider = "ds",
 *   tags = {
 *     "entity_display",
 *     "view"
 *   }
 * )
 */
class DsDynamicFieldSource extends AbstractDsFieldSource {

  /**
   * {@inheritdoc}
   */
  public function getSourceFields() {
    $sources = [];
    $fields = $this->dsManager->getDefinitions();

    foreach ($fields as $name => $field) {
      if (in_array($field['id'], ['dynamic_token_field', 'dynamic_block_field'])) {
        $sources[] = $this->getSourceField($name, $field['title']);
      }
    }
    return $sources;
  }

}
