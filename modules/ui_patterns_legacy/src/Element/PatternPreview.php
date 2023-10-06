<?php

namespace Drupal\ui_patterns_legacy\Element;

/**
 * Renders a pattern preview element.
 *
 * @RenderElement("pattern_preview")
 */
class PatternPreview extends Pattern {

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    return [
      '#pre_render' => [
        [$this, 'convert'],
        [$this, 'loadPreviewStory'],
        [$this, 'preRenderComponent'],
      ],
      '#component' => '',
      '#props' => [],
      '#slots' => [],
      '#propsAlter' => [],
      '#slotsAlter' => [],
    ];
  }

  /**
   * Load preview.
   *
   * @param array $element
   *   Render array.
   *
   * @return array
   *   Render array.
   */
  public function loadPreviewStory(array $element): array {
    $manager = \Drupal::service('plugin.manager.sdc');
    $component = $manager->getDefinition($element["#component"]);
    if (!isset($component["stories"])) {
      return $element;
    }
    if (empty($component["stories"])) {
      return $element;
    }
    $story_id = self::getStoryId($component["stories"]);
    $story = $component["stories"][$story_id];
    $slots = $story["slots"] ?? [];
    $props = $story["props"] ?? [];
    $slots = array_merge($element["#slots"], $slots);
    $element["#slots"] = $manager::processStoriesSlots($slots);
    $element["#props"] = array_merge($element["#props"], $props);
    return $element;
  }

  /**
   *
   */
  private function getStoryId(array $stories): string {
    // In UI Patterns 1.x, there was only one story by component, called "preview".
    if (array_key_exists("preview", $stories)) {
      return "preview";
    }
    return array_key_first($stories);
  }

}
