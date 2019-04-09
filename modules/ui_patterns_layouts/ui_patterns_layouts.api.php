<?php

/**
 * @file
 * API file.
 */

use Drupal\ui_patterns\Definition\PatternDefinition;

/**
 * Alter "Manage display" layout form.
 *
 * @param array $fieldset
 *   Pattern settings fieldset.
 * @param \Drupal\ui_patterns\Definition\PatternDefinition $definition
 *   Pattern definition.
 * @param array $configuration
 *   Pattern configuration.
 *
 */
function hook_ui_patterns_layout_form_alter(array &$fieldset, PatternDefinition $definition, array $configuration) {
  $fieldset['element'] = ['#type' => 'input'];
}
