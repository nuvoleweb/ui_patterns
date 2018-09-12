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
      '#multiple_sources' => FALSE,
      '#pre_render' => [
        [$class, 'processContext'],
        [$class, 'processRenderArray'],
        [$class, 'processLibraries'],
        [$class, 'processMultipleSources'],
        [$class, 'processFields'],
        [$class, 'processUse'],
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
    // Return empty element, if pattern is not found.
    if (!static::exists($element)) {
      if (!isset($element['#id'])) {
        \Drupal::logger('ui_patterns')->error('UI pattern plugin requested without identifier.');
      }
      else {
        \Drupal::logger('ui_patterns')->error('UI pattern plugin %plugin_id not found.', [
          '%plugin_id' => $element['#id'],
        ]);
      }

      return [];
    }

    $element['#theme'] = UiPatterns::getPatternDefinition($element['#id'])->getThemeHook();

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
    // Skip processing if pattern does not exist.
    if (!static::exists($element)) {
      return $element;
    }

    foreach (UiPatterns::getPatternDefinition($element['#id'])->getLibrariesNames() as $library) {
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
    // Skip processing if pattern does not exist.
    if (!static::exists($element)) {
      return $element;
    }

    // Make sure we don't render anything in case fields are empty.
    if (self::hasFields($element)) {
      $fields = $element['#fields'];
      unset($element['#fields']);

      foreach ($fields as $name => $field) {
        $key = '#' . $name;
        $element[$key] = $field;
      }
    }
    else {
      $element['#markup'] = '';
    }
    return $element;
  }

  /**
   * Process use property.
   *
   * @param array $element
   *   Render array.
   *
   * @return array
   *   Render array.
   */
  public static function processUse(array $element) {
    // Skip processing if pattern does not exist.
    if (!static::exists($element)) {
      return $element;
    }

    $definition = UiPatterns::getPatternDefinition($element['#id']);
    if ($definition->hasUse()) {
      $element['#use'] = $definition->getUse();
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
  public static function processMultipleSources(array $element) {
    // Skip processing if pattern does not exist.
    if (!static::exists($element)) {
      return $element;
    }

    // Make sure we don't render anything in case fields are empty.
    if (self::hasFields($element) && self::hasMultipleSources($element)) {
      foreach ($element['#fields'] as $name => $field) {
        // This guarantees backward compatibility: single sources be simple.
        $element['#fields'][$name] = reset($field);
        if (count($field) > 1) {
          /** @var \Drupal\ui_patterns\Element\PatternContext $context */
          $context = $element['#context'];
          $context->setProperty('pattern', $element['#id']);
          $context->setProperty('field', $name);

          // Render multiple sources with "patterns_destination" template.
          $element['#fields'][$name] = [
            '#sources' => $field,
            '#context' => $context,
            '#theme' => 'patterns_destination',
          ];
        }
      }
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
    // Skip processing if pattern does not exist.
    if (!static::exists($element)) {
      return $element;
    }

    if (self::hasValidContext($element)) {
      $context = $element['#context'];
      $element['#context'] = new PatternContext($context['type'], $element['#context']);
    }
    else {
      $element['#context'] = new PatternContext('empty');
    }

    return $element;
  }

  /**
   * Whether the given pattern exists or not.
   *
   * @param array $element
   *   Render array.
   *
   * @return bool
   *   TRUE or FALSE.
   */
  public static function exists(array $element) {
    return isset($element['#id']) && UiPatterns::getManager()->hasDefinition($element['#id']);
  }

  /**
   * Whereas pattern has field or not.
   *
   * @param array $element
   *   Render array.
   *
   * @return bool
   *   TRUE or FALSE.
   */
  public static function hasFields(array $element) {
    return isset($element['#fields']) && !empty($element['#fields']) && is_array($element['#fields']);
  }

  /**
   * Whereas pattern fields can accept multiple sources.
   *
   * @param array $element
   *   Render array.
   *
   * @return bool
   *   TRUE or FALSE.
   */
  public static function hasMultipleSources(array $element) {
    return isset($element['#multiple_sources']) && $element['#multiple_sources'] === TRUE;
  }

  /**
   * Whereas pattern has a valid context, i.e. context "type" is set.
   *
   * @param array $element
   *   Render array.
   *
   * @return bool
   *   TRUE or FALSE.
   */
  public static function hasValidContext(array $element) {
    return isset($element['#context']) && is_array($element['#context']) && !empty($element['#context']['type']);
  }

}
