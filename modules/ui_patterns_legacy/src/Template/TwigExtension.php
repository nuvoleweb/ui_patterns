<?php

namespace Drupal\ui_patterns_legacy\Template;

use Drupal\ui_patterns_legacy\UiPatternsLegacyManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension providing UI Patterns-specific functionalities.
 *
 * @package Drupal\ui_patterns\Template
 */
class TwigExtension extends AbstractExtension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'ui_patterns_legacy';
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
    $component = UiPatternsLegacyManager::getComponentByUiPatternId($id);
    if ($component) {
      return [
        '#type' => 'component',
        '#id' => $id,
        '#slots' => $fields,
        '#variant' => $variant,
      ];
    }
    return [
      '#type' => 'markup',
      '#markup' => 'No component found for ' . $id,
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
    $component = UiPatternsLegacyManager::getComponentByUiPatternId($id);
    if ($component) {
      return [
        '#type' => 'component',
        '#id' => $component['id'],
        '#variant' => $variant,
      ];
    }
    return [
      '#type' => 'markup',
      '#markup' => 'No component found for ' . $id,
    ];
  }

}
