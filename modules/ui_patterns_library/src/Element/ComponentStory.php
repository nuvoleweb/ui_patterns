<?php

namespace Drupal\ui_patterns_library\Element;

use Drupal\sdc\Element\ComponentElement;

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
    // @todo Load slots & props from a component story.
    return $element;
  }

}
