<?php

namespace Drupal\ui_patterns;

/**
 * UI Patterns factory class.
 *
 * @package Drupal\ui_patterns
 */
class UiPatterns {

  /**
   * Get pattern manager instance.
   *
   * @return \Drupal\ui_patterns\UiPatternsManager
   *   UI Patterns manager instance.
   */
  public static function getManager() {
    return \Drupal::service('plugin.manager.ui_patterns');
  }

  /**
   * Get pattern field sources manager instance.
   *
   * @return \Drupal\ui_patterns\UiPatternsSourceManager
   *   UI Patterns field sources manager instance.
   */
  public static function getSourceManager() {
    return \Drupal::service('plugin.manager.ui_patterns_source');
  }

  /**
   * Get pattern definition.
   *
   * @param string $id
   *   Pattern ID.
   *
   * @return \Drupal\ui_patterns\Definition\PatternDefinition
   *   Pattern object instance.
   */
  public static function getPatternDefinition($id) {
    return \Drupal::service('plugin.manager.ui_patterns')->getDefinition($id);
  }

  /**
   * Get pattern definitions.
   *
   * @return \Drupal\ui_patterns\Definition\PatternDefinition[]
   *   Pattern object instance.
   */
  public static function getPatternDefinitions() {
    return \Drupal::service('plugin.manager.ui_patterns')->getDefinitions();
  }

}
