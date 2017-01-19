<?php

namespace Drupal\ui_patterns\Element;

use \Drupal\Core\Render\Markup;

/**
 * Renders a pattern preview element.
 *
 * @RenderElement("pattern_preview")
 */
class PatternPreview extends Pattern {

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
    $fields = [];
    foreach (self::$definition['fields'] as $name => $field) {
      // Some fields are used as twig array keys and don't need escaping.
      if (!isset($field['escape']) || $field['escape'] != FALSE) {
        // The examples are not user submitted and are safe markup.
        $field['preview'] = self::getPreviewMarkup($field['preview']);
      }

      $fields[$name] = $field['preview'];
    }

    if (isset(self::$definition['extra']['attributes'])) {
      $fields['attributes'] = self::$definition['extra']['attributes'];
    }
    $element['#fields'] = $fields;

    return parent::processFields($element);
  }

  /**
   * Make previews markup safe.
   *
   * @param string|string[] $preview
   *   The preview, may be a string or an array.
   *
   * @return array|\Drupal\Component\Render\MarkupInterface|string
   *   Preview safe markup.
   */
  protected static function getPreviewMarkup($preview) {
    if (is_array($preview)) {
      // Check if preview is a render array.
      if (array_key_exists('theme', $preview) || array_key_exists('type', $preview)) {
        $rendered = [];
        foreach ($preview as $key => $value) {
          $rendered['#' . $key] = $value;
        }

        return $rendered;
      }

      // Recursively escape the string elements.
      return array_map([self::class, __METHOD__], $preview);
    }

    return Markup::create($preview);
  }

}
