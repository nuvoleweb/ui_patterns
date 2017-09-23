<?php

/**
 * @file
 * API file.
 */

use Drupal\ui_patterns\Element\PatternContext;

/**
 * Alter UI Patterns definitions.
 *
 * @param \Drupal\ui_patterns\Definition\PatternDefinition[] $definitions
 *    Pattern definitions.
 *
 * @see \Drupal\ui_patterns\UiPatternsManager
 */
function hook_ui_patterns_info_alter(&$definitions) {
  $definitions['my_pattern']->setLabel('My new label');
}

/**
 * Alter UI Patterns Source definitions.
 *
 * @see \Drupal\ui_patterns\UiPatternsSourceManager
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

/**
 * Provide hook theme suggestions for patterns destination wrapper.
 *
 * A pattern render element having '#multiple_sources' set to TRUE can render
 * multiple sources on the same destination field. Sources will be rendered
 * using the 'patterns_destination' theme function which will use the
 * 'patterns-destination.html.twig' template file.
 *
 * Developers can take over rendering of the template above by providing proper
 * suggestions, this is useful in case you wish to provide separators or other
 * wrapping elements.
 *
 * @see ui_patterns_theme_suggestions_alter()
 * @see \Drupal\ui_patterns\Element\Pattern::processMultipleSources()
 */
function hook_ui_patterns_destination_suggestions_alter(array &$suggestions, array $variables, PatternContext $context) {
  if ($context->isOfType('views_row')) {
    $hook = $variables['theme_hook_original'];
    $view_name = $context->getProperty('view_name');
    $display = $context->getProperty('display');
    $pattern = $context->getProperty('pattern');
    $field = $context->getProperty('field');

    $suggestions[] = $hook . '__views_row__' . $view_name . '__' . $pattern . '__' . $field;
    $suggestions[] = $hook . '__views_row__' . $view_name . '__' . $display . '__' . $pattern . '__' . $field;
  }
}
