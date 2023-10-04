<?php

namespace Drupal\ui_patterns_legacy\Template;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension providing UI Patterns-specific functionalities.
 *
 * @package Drupal\ui_patterns_legacy\Template
 */
class TwigExtension extends AbstractExtension {

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return 'ui_patterns_legacy';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions(): array {
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
   * @see \Drupal\ui_patterns_legacy\Element\Pattern
   */
  public function renderPattern(string $id, array $fields = [], $variant = ""): array {
    return [
      '#type' => 'pattern',
      '#id' => $id,
      '#variant' => $variant,
      '#fields' => $fields,
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
   * @see \Drupal\ui_patterns_legacy\Element\PatternPreview
   */
  public function renderPatternPreview(string $id, string $variant = ""): array {
    return [
      '#type' => 'pattern_preview',
      '#id' => $id,
      '#variant' => $variant,
    ];
  }

}
