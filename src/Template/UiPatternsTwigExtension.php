<?php

namespace Drupal\ui_patterns\Template;

/**
 * Class UiPatternsTwigExtension.
 *
 * @package Drupal\ui_patterns\Template
 */
class UiPatternsTwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('pattern', [$this, 'renderPattern']),
    ];
  }

  /**
   * Render given pattern.
   *
   * @param string $id
   *    Pattern ID.
   * @param array $fields
   *    Pattern fields.
   *
   * @return array
   *    Pattern render array.
   *
   * @see \Drupal\ui_patterns\Element\Pattern
   */
  public function renderPattern($id, $fields = []) {
    return [
      '#type' => 'pattern',
      '#id' => $id,
      '#fields' => $fields,
    ];
  }

}
