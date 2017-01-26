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
      $rendered = [];
      $hashKeys = array_key_exists('theme', $preview) || array_key_exists('type', $preview);
      foreach ($preview as $key => $value) {
        if ($hashKeys) {
          $key = '#' . $key;
        }
        if (is_array($value)) {
          $value = self::getPreviewMarkup($value);
        }
        $rendered[$key] = $value;
      }

      return $rendered;
    }

    return Markup::create($preview);
  }

}
