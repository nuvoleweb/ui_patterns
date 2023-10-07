<?php

namespace Drupal\ui_patterns\Sdc;

/**
 * Plugin Manager for....
 *
 * @see plugin_api
 *
 * @internal
 */
class UiPatternsSdcPluginManager extends ComponentPluginManagerDecorator {

  /**
   * {@inheritdoc}
   */
  protected function getCacheKey() {
    return 'ui_patterns';
  }

  /**
   * {@inheritdoc}
   */
  protected function alterDefinitions(&$definitions) {
    parent::alterDefinitions($definitions);
    foreach ($definitions as $component_id => $definition) {
      if (!isset($definition['props'])) {
        continue;
      }
      if (!isset($definition['props']['properties'])) {
        continue;
      }
      foreach ($definition['props']['properties'] as $prop_id => $prop) {
        $prop_type = $this->propTypePluginManager->getPropTypePlugin($prop);
        $sources = $this->sourcePluginManager->getSourcePlugins($prop_type->getPluginId(), $prop_id, $prop);
        $prop['ui_patterns']['type_definition'] = $prop_type;
        $prop['ui_patterns']['source'] = $sources;
        $definition['props']['properties'][$prop_id] = $prop;
      }
      $definitions[$component_id] = $definition;
    }
  }

  /**
   * Do we move this method to ui_patterns_legacy?
   */
  public function getNamespacedId(string $component_id): string {
    $parts = explode(":", $component_id);
    if (count(array_filter($parts)) === 2) {
      // Already namespaced.
      return $component_id;
    }
    if (count(array_filter($parts)) > 2) {
      // Unexpected situation.
      return $component_id;
    }
    $components = $this->getAllComponents();
    // @todo Search first in current active theme, then parents themes, then modules.
    foreach ($components as $component) {
      if ($component->getPluginDefinition()["machineName"] === $component_id) {
        return $component->getPluginId();
      }
    }
    return $component_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedDefinitions(?array $definitions = NULL): array {
    // @todo use category metadata from ui_patterns_library
    // Do we move this method to ui_patterns_library?
    // Or do we move categories to ui_patterns?
    $definitions = $definitions ?: $this->getDefinitions();
    $groups = [];
    foreach ($definitions as $id => $definition) {
      $category = $definition["category"] ?? "Other";
      $groups[$category][$id] = $definition;
    }
    return $groups;
  }

  /**
   * Stories slots have no "#" prefix in render arrays. Let's add them.
   * A bit like UI Patterns 1.x's PatternPreview::getPreviewMarkup()
   * This method belongs here because sued by both ui_patterns_library and
   * ui_patterns_legacy.
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
