<?php

/**
 * Alter UI Patterns definitions.
 *
 * @see \Drupal\ui_patterns\UiPatternsManager
 */
function hook_ui_patterns_info_alter(&$definitions) {
  $definitions['my_pattern']['label'] = 'My new label';
}

/**
 * Alter UI Patterns Source definitions.
 *
 * @see \Drupal\ui_patterns\Plugin\UiPatternsSourceManager
 */
function hook_ui_patterns_ui_patterns_source_info_alter(&$definitions) {
  $definitions['my_field_source']['tags'][] = 'new_tag';
}
