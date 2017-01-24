<?php

use Drupal\ui_patterns\Element\PatternContext;

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

/**
 * Provide hook theme suggestions for patterns.
 *
 * @see ui_patterns_theme_suggestions_alter()
 */
function hook_ui_patterns_suggestions_alter(array &$suggestions, array $variables, PatternContext $context) {
  if ($context->isOfType('views_row')) {
    $hook = $variables['theme_hook_original'];
    $view_name = $context->getProperty('view_name');
    $display = $context->getProperty('display');

    $suggestions[] = $hook . '__views_row__' . $view_name;
    $suggestions[] = $hook . '__views_row__' . $view_name . '__' . $display;
  }
}
