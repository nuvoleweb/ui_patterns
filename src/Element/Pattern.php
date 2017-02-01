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
  static public $definition;

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
        [$class, 'processLibraries'],
        [$class, 'processFields'],
        [$class, 'processContext'],
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

    unset($element['#type'], $element['#id']);
    return $element;
  }

  /**
   * Process libraries.
   *
   * @param array $element
   *   Render array.
   *
   * @return array
   *   Render array.
   */
  public static function processLibraries(array $element) {
    $id = self::$definition['id'];
    $libraries = self::$definition['libraries'];
    foreach ($libraries as $library) {
      if (is_array($library)) {
        $element['#attached']['library'][] = 'ui_patterns/' . $id . '.' . key($library);
      }
      else {
        $element['#attached']['library'][] = $library;
      }
    }

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

  /**
   * Process context.
   *
   * @param array $element
   *   Render array.
   *
   * @return array
   *   Render array.
   *
   * @throws \Drupal\ui_patterns\Exception\PatternRenderException
   *    Throws an exception if no context type is specified.
   */
  public static function processContext(array $element) {

    if (isset($element['#context']) && !empty($element['#context']) && is_array($element['#context']) && isset($element['#context']['type']) && !empty($element['#context']['type'])) {
      $context = $element['#context'];
      $element['#context'] = new PatternContext($context['type'], $element['#context']);
    }
    else {
      $element['#context'] = new PatternContext('empty');
    }

    return $element;
  }

}
