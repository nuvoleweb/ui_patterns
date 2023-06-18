<?php

namespace Drupal\ui_patterns\Template;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

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
      new TwigFunction('pattern', [
        $this,
        'renderPattern',
      ]),
      new TwigFunction('pattern_preview', [
        $this,
        'renderPatternPreview',
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
   * Render given pattern.
   *
   * @param string $id
   *   Pattern ID.
   * @param array $fields
   *   Pattern fields.
   * @param string $variant
   *   Variant name.
   *
   * @return array
   *   Pattern render array.
   *
   * @see \Drupal\ui_patterns\Element\Pattern
   */
  public function renderPattern($id, array $fields = [], $variant = "") {
    return [
      '#type' => 'pattern',
      '#id' => $id,
      '#fields' => $fields,
      '#variant' => $variant,
    ];
  }

  /**
   * Render given pattern.
   *
   * @param string $id
   *   Pattern ID.
   * @param string $variant
   *   Variant name.
   *
   * @return array
   *   Pattern render array.
   *
   * @see \Drupal\ui_patterns\Element\Pattern
   */
  public function renderPatternPreview($id, $variant = "") {
    return [
      '#type' => 'pattern_preview',
      '#id' => $id,
      '#variant' => $variant,
    ];
  }

}
