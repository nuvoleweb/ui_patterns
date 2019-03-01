<?php

namespace Drupal\ui_patterns\Element;

use Drupal\Core\Render\Markup;
use Drupal\ui_patterns\UiPatterns;

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
    $definition = UiPatterns::getPatternDefinition($element['#id']);

    $fields = [];
    foreach ($definition->getFields() as $field) {
      $preview = $field->getPreview();
      // Some fields are used as Twig array keys and don't need escaping.
      if ($field->getEscape()) {
        // The examples are not user submitted and are safe markup.
        $preview = self::getPreviewMarkup($preview);
      }

      $fields[$field->getName()] = $preview;
    }

    if (isset($definition['additional']['attributes'])) {
      $fields['attributes'] = $definition['extra']['attributes'];
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
  public static function getPreviewMarkup($preview) {
    if (is_array($preview)) {
      $rendered = [];
      // If preview is a render array add hashes to keys.
      $hash_keys = array_key_exists('theme', $preview) || array_key_exists('type', $preview);
      foreach ($preview as $key => $value) {
        $key = $hash_keys ? '#' . $key : $key;
        if (is_array($value)) {
          // Process array values recursively.
          $value = self::getPreviewMarkup($value);
        }
        $rendered[$key] = $value;
      }

      return $rendered;
    }

    return Markup::create($preview);
  }

  /**
   * {@inheritdoc}
   */
  public static function processContext(array $element) {
    $element['#context'] = new PatternContext('preview');

    return $element;
  }

}
