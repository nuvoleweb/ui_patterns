<?php

namespace Drupal\ui_patterns_field_group\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\Plugin\PatternSourceBase;

/**
 * Defines Fields API pattern source plugin.
 *
 * @UiPatternsSource(
 *   id = "fieldgroup",
 *   label = @Translation("Fieldgroups"),
 *   provider = "field",
 *   tags = {
 *     "entity_display"
 *   }
 * )
 */
class FieldgroupSource extends PatternSourceBase {

  /**
   * Return list of source fields.
   *
   * @return \Drupal\ui_patterns\Definition\PatternSourceField[]
   *   List of source fields.
   */
  public function getSourceFields() {
    $sources = [];
    $entity_type_id = $this->getContextProperty('entity_type');
    $bundle = $this->getContextProperty('entity_bundle');
    $view_mode = $this->getContextProperty('entity_view_mode');

    $groups = field_group_info_groups($entity_type_id, $bundle, 'view', $view_mode);

    foreach ($groups as $group_name => $group) {
      if (!$this->getContextProperty('limit')) {
        $sources[] = $this->getSourceField($group_name, $group->label);
      }
      elseif (in_array($group_name, $this->getContextProperty('limit'))) {
        $sources[] = $this->getSourceField($group_name, $group->label);
      }
    }

    return $sources;
  }

}

