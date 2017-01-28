<?php

namespace Drupal\ui_patterns\Template;

/**
 * Class UiPatternsTwigExtension.
 *
 * @package Drupal\ui_patterns\Template
 */
class TwigExtension extends \Twig_Extension {

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
      new \Twig_SimpleFunction('pattern', [$this, 'renderPattern']),
      new \Twig_SimpleFunction('pattern_preview', [$this, 'renderPatternPreview']),
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

  /**
   * Render given pattern.
   *
   * @param string $id
   *    Pattern ID.
   *
   * @return array
   *    Pattern render array.
   *
   * @see \Drupal\ui_patterns\Element\Pattern
   */
  public function renderPatternPreview($id) {
    return [
      '#type' => 'pattern_preview',
      '#id' => $id,
    ];
  }

}
