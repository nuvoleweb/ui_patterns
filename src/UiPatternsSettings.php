<?php

namespace Drupal\ui_patterns;

/**
 * UI Patterns setting factory class.
 *
 * @package Drupal\ui_patterns
 */
class UiPatternsSettings {

  /**
   * Get pattern manager setting instance.
   *
   * @return \Drupal\ui_patterns\UiPatternsSettingsManager
   *    UI Patterns setting manager instance.
   */
  public static function getManager() {
    return \Drupal::service('plugin.manager.ui_patterns_settings');
  }

  /**
   * Preprocess setiting variables. Called before rendered
   *
   * @param string $pattern_id
   *    Pattern ID for which to preprocess
   * @param array $settings
   *    The stored settings
   * @param \Drupal\Core\Entity\Entity $entity
   *    The entity of the pattern. Useful for dynamic settings
   */
  public static function preprocess($pattern_id, $settings, $entity = NULL) {
    $processed_settings = [];
    $pattern = UiPatterns::getPatternDefinition($pattern_id);
    $context = [];
    $context['entity'] = $entity;
    $pattern_settings = $pattern->getSettings();
    foreach ($pattern_settings as $key => $settingDefinition) {
      if ($settingDefinition->getForcedValue()) {
        $value = $settingDefinition->getForcedValue();
      }
      elseif (isset($settings[$key])) {
        $value = $settings[$key];
      }
      else {
        $value = $settingDefinition->getDefaultValue();
      }
      $settingType = UiPatternsSettings::createSettingType($settingDefinition);
      $processed_settings[$key] = $settingType->preprocess($value, $context);
    }
    return $processed_settings;

  }

  /**
   * Create setting type plugin
   *
   * @param \Drupal\ui_patterns\Definition\PatternDefinitionSetting $settingDefintion
   *    The setting defintion.
   * @return \Drupal\ui_patterns\Plugin\PatternSettingTypeInterface
   *    UI Patterns setting manager instance.
   */
  public static function createSettingType($settingDefintion) {
    $configuration = [];
    $configuration['pattern_setting_definition'] = $settingDefintion;
    return \Drupal::service('plugin.manager.ui_patterns_settings')
      ->createInstance($settingDefintion->getType(), $configuration);
  }


}
