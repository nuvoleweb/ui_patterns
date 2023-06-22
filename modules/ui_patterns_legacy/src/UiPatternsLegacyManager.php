<?php

namespace Drupal\ui_patterns_legacy;

/**
 * UiPatternsLegacyManager provides static helper functions.
 */
class UiPatternsLegacyManager {

  /**
   * Returns SDC component by UI Pattern id.
   *
   * @param $ui_pattern_id
   *   The ui_pattern_id id
   *
   * @return \Drupal\sdc\Component\ComponentMetadata|void
   *   The SDC.
   */
  public static function getComponentByUiPatternId($ui_pattern_id) {
    /** @var \Drupal\sdc\ComponentPluginManager $plugin_manager */
    $plugin_manager = \Drupal::service('plugin.manager.sdc');
    /** @var \Drupal\sdc\Component\ComponentMetadata[] $components */
    $components = $plugin_manager->getDefinitions();
    foreach ($components as $component) {
      if (isset($component['ui_pattern_id']) && $component['ui_pattern_id'] === $ui_pattern_id) {
        return $component;
      }
    }
  }

}
