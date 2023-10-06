<?php

namespace Drupal\ui_patterns_library\Element;

use Drupal\ui_patterns\Element\ComponentElement;

/**
 * Renders a component story.
 *
 * @RenderElement("component_story")
 */
class ComponentStory extends ComponentElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    return [
      '#pre_render' => [
        [$this, 'loadStory'],
        [$this, 'preRenderComponent'],
      ],
      '#component' => '',
      '#story' => '',
      '#props' => [],
      '#slots' => [],
      '#propsAlter' => [],
      '#slotsAlter' => [],
    ];
  }

  /**
   *
   */
  public function loadStory(array $element): array {
    $manager = \Drupal::service('plugin.manager.sdc');
    if (!isset($element["#story"])) {
      return $element;
    }
    $story_id = $element["#story"];
    $component = $manager->getDefinition($element["#component"]);
    if (!isset($component["stories"])) {
      return $element;
    }
    if (!isset($component["stories"][$story_id])) {
      return $element;
    }
    $story = $component["stories"][$story_id];
    $slots = $story["slots"] ?? [];
    $props = $story["props"] ?? [];
    $slots = array_merge($element["#slots"], $slots);
    $element["#slots"] = $manager::processStoriesSlots($slots);
    $element["#props"] = array_merge($element["#props"], $props);
    return $element;
  }

}
