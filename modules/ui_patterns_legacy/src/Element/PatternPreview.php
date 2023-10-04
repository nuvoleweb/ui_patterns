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
        [$this, 'loadPreviewStory'],
        [$this, 'convert'],
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
    // @todo Load slots as fields & props as settings from 'preview' story.
    return $element;
  }

}
