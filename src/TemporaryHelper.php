<?php

namespace Drupal\ui_patterns;

/**
 * Move all this to a component plugin maanger service .
 */
class TemporaryHelper {

  /**
   *
   */
  public static function getNamespacedId(string $component_id): string {
    $parts = explode(":", $component_id);
    if (count(array_filter($parts)) === 2) {
      // Already namespaced.
      return $component_id;
    }
    if (count(array_filter($parts)) > 2) {
      // Unexpected situation.
      return $component_id;
    }
    $components = \Drupal::service('plugin.manager.sdc')->getAllComponents();
    // @todo Search first in current active theme, then parents themes, then modules.
    foreach ($components as $component) {
      if ($component->getPluginDefinition()["machineName"] === $component_id) {
        return $component->getPluginId();
      }
    }
    return $component_id;
  }

  /**
   *
   */
  public static function getGroupedDefinitions(): array {
    $definitions = [];
    // @todo use category metadata from ui_patterns_library
    // Do we move this method to ui_patterns_library?
    foreach (\Drupal::service('plugin.manager.sdc')->getAllComponents() as $component) {
      $definitions[] = $component->getPluginDefinition();
    }
    $groups = [
      "All" => $definitions,
    ];
    return $groups;
  }

}
