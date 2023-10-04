<?php

namespace Drupal\ui_patterns\Template;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig extension providing UI Patterns-specific functionalities.
 *
 * @package Drupal\ui_patterns\Template
 */
class TwigExtension extends AbstractExtension {

  use AttributesFilterTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'ui_patterns';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new TwigFunction('component', [
        $this,
        'renderComponent',
      ]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    return [
      new TwigFilter('add_class', [$this, 'addClass']),
      new TwigFilter('set_attribute', [$this, 'setAttribute']),
    ];
  }

  /**
   * Render given component.
   *
   * @param string $component_id
   *   Component ID.
   * @param array $slots
   *   Pattern slots.
   * @param array $props
   *   Pattern props.
   *
   * @return array
   *   Pattern render array.
   *
   * @see \Drupal\sdc\Element\ComponentElement
   */
  public function renderComponent(string $component_id, array $slots = [], array $props = []) {
    return [
      '#type' => 'component',
      '#component' => $component_id,
      '#slots' => $slots,
      '#props' => $props,
    ];
  }

}
