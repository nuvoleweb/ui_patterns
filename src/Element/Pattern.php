<?php

namespace Drupal\ui_patterns\Element;

use Drupal\Core\Render\Element\RenderElement;
use Drupal\Core\Template\Attribute;
use Drupal\ui_patterns\UiPatterns;

/**
 * Renders a pattern element.
 *
 * @RenderElement("pattern")
 */
class Pattern extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#input' => FALSE,
      '#pre_render' => [
        [$class, 'processRenderArray'],
        [$class, 'processLibraries'],
        [$class, 'processFields'],
        [$class, 'processContext'],
      ],
    ];
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
    $element['#theme'] = UiPatterns::getPattern($element['#id'])->getThemeHook();

    if (isset($element['#attributes']) && !empty($element['#attributes']) && is_array($element['#attributes'])) {
      $element['#attributes'] = new Attribute($element['#attributes']);
    }
    else {
      $element['#attributes'] = new Attribute();
    }

    unset($element['#type']);
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
    foreach (UiPatterns::getPattern($element['#id'])->getLibraries() as $library) {
      $element['#attached']['library'][] = $library;
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
        $key = '#' . $name;

        // This guarantees backward compatibility: single sources be single.
        if (count($field) == 1) {
          $element[$key] = reset($field);
        }
        else {
          // Render multiple sources with "patterns_destination" template.
          $element[$key]['#sources'] = $field;
          $element[$key]['#context']['pattern'] = $element['#id'];
          $element[$key]['#context']['field'] = $name;
          $element[$key]['#theme'] = 'patterns_destination';
        }
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
