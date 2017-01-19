<?php

namespace Drupal\ui_patterns\Element;

use Drupal\Core\Render\Element\RenderElement;
use Drupal\Core\Template\Attribute;

/**
 * Renders a pattern element.
 *
 * @RenderElement("pattern")
 */
class Pattern extends RenderElement {

  /**
   * Current pattern definition.
   *
   * @var array
   */
  static protected $definition;

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#input' => FALSE,
      '#pre_render' => [
        [$class, 'setDefinition'],
        [$class, 'processRenderArray'],
        [$class, 'processFields'],
      ],
    ];
  }

  /**
   * Set pattern definition.
   *
   * @param array $element
   *   Render array.
   *
   * @return array
   *   Render array.
   */
  public static function setDefinition(array $element) {
    self::$definition = \Drupal::service('plugin.manager.ui_patterns')->getDefinition($element['#id']);
    return $element;
  }

  /**
   * Process render array.
   *
   * @param array $element
   *   Render array.
   *
   * @return array
   *   Render array.
   */
  public static function processRenderArray(array $element) {
    $element['#theme'] = self::$definition['theme hook'];

    if (isset($element['#attributes']) && !empty($element['#attributes']) && is_array($element['#attributes'])) {
      $element['#attributes'] = new Attribute($element['#attributes']);
    }
    else {
      $element['#attributes'] = new Attribute();
    }

    foreach (self::$definition['libraries'] as $library) {
      $element['#attached']['library'][] = $library;
    }

    unset($element['#type'], $element['#id']);
    return $element;
  }

  /**
   * Process fields.
   *
   * @param array $element
   *   Render array.
   *
   * @return array
   *   Render array.
   */
  public static function processFields(array $element) {
    // Make sure we don't render anything in case fields are empty.
    if (isset($element['#fields']) && !empty($element['#fields'])) {
      $fields = $element['#fields'];
      unset($element['#fields']);
      foreach ($fields as $name => $field) {
        $element["#{$name}"] = $field;
      }
    }
    else {
      $element['#markup'] = '';
    }
    return $element;
  }

}
