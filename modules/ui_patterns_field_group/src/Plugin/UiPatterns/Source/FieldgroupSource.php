<?php

namespace Drupal\ui_patterns_field_group\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\Plugin\PatternSourceBase;

/**
 * Defines Fields API pattern source plugin.
 *
 * @UiPatternsSource(
 *   id = "fieldgroup",
 *   label = @Translation("Fieldgroups"),
 *   provider = "field_group",
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
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public function getSourceFields() {
    $sources = [];
    $entity_type_id = $this->getContextProperty('entity_type');
    $bundle = $this->getContextProperty('entity_bundle');
    $view_mode = $this->getContextProperty('entity_view_mode');

    $groups = field_group_info_groups($entity_type_id, $bundle, 'view', $view_mode);

    foreach ($groups as $group_name => $group) {
      if (empty($this->getContextProperty('limit')) || in_array($group_name, $this->getContextProperty('limit'))) {
        $sources[] = $this->getSourceField($group_name, $group->label);
      }
    }
    $sources[] = $this->getSourceField('_label', 'Group label');

    return $sources;
  }

}
