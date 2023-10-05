<?php

namespace Drupal\ui_patterns_library\Template;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension providing UI Patterns Legacy functionalities.
 *
 * @package Drupal\ui_patterns_library\Template
 */
class TwigExtension extends AbstractExtension {

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return 'ui_patterns_library';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions(): array {
    return [
      new TwigFunction('component_story', [
        $this,
        'renderComponentStory',
      ]),
    ];
  }

  /**
   * Render given component story.
   *
   * @param string $component_id
   *   Component ID.
   * @param string $story_id
   *   Story ID.
   * @param array $slots
   *   Component slots to override.
   * @param array $props
   *   Component props to override.
   *
   * @return array
   *   Pattern render array.
   *
   * @see \Drupal\sdc\Element\ComponentElement
   */
  public function renderComponentStory(string $component_id, string $story_id, array $slots = [], array $props = []) {
    return [
      '#type' => 'component_story',
      '#component' => $component_id,
      '#story' => $story_id,
      '#slots' => $slots,
      '#props' => $props,
    ];
  }

}
