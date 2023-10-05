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

  /**
   * Stories slots have no "#" prefix in render arrays. Let's add them.
   * A bit like UI Patterns 1.x's PatternPreview::getPreviewMarkup()
   * Do we move this method to ui_patterns_library?
   * Is iy something we want to remove in UI Patterns 2?
   */
  public static function processStoriesSlots(array $slots): array {
    foreach ($slots as $slot_id => $slot) {
      if (!is_array($slot)) {
        continue;
      }
      if (array_is_list($slot)) {
        $slots[$slot_id] = self::processStoriesSlots($slot);
      }
      $slot_keys = array_keys($slot);
      $render_keys = ["theme", "type", "markup", "plain_text"];
      if (count(array_intersect($slot_keys, $render_keys)) > 0) {
        foreach ($slot as $key => $value) {
          if (is_array($value)) {
            $value = self::processStoriesSlots($value);
          }
          $slots[$slot_id]["#" . $key] = $value;
          unset($slots[$slot_id][$key]);
        }
      }
    }
    return $slots;
  }

}
