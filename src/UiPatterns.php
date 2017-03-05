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
   *    UI Patterns manager instance.
   */
  public static function getManager() {
    return \Drupal::service('plugin.manager.ui_patterns');
  }

  /**
   * Get pattern field sources manager instance.
   *
   * @return \Drupal\ui_patterns\Plugin\UiPatternsSourceManager
   *    UI Patterns field sources manager instance.
   */
  public static function getSourceManager() {
    return \Drupal::service('plugin.manager.ui_patterns_source');
  }

  /**
   * Get pattern object.
   *
   * @param string $id
   *    Pattern ID.
   *
   * @return \Drupal\ui_patterns\UiPatternBase
   *    Pattern object instance.
   */
  public static function getPattern($id) {
    return \Drupal::service('plugin.manager.ui_patterns')->getPattern($id);
  }

  /**
   * Get pattern definition object.
   *
   * @param array $data
   *    Pattern definition array.
   *
   * @return \Drupal\ui_patterns\Plugin\DataType\Pattern
   *    Pattern definition object.
   */
  public static function getPatternDefinition(array $data) {
    $manager = \Drupal::typedDataManager();
    $definition = $manager->createDataDefinition('ui_patterns_pattern');
    return $manager->create($definition, $data);
  }

}
