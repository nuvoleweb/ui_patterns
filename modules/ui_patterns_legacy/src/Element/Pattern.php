<?php

namespace Drupal\ui_patterns_legacy\Element;

use Drupal\Core\Render\Element;
use Drupal\ui_patterns\Element\ComponentElement;

/**
 * Renders a pattern element as a SDC element.
 *
 * @RenderElement("pattern")
 */
class Pattern extends ComponentElement {

  const COMMON_RENDER_PROPERTIES = [
    "#type",
    "#id",
    "#settings",
    "#fields",
    "#printed",
    "#input",
    "#pre_render",
    "#cache",
    "#context",
    "#attached",
  ];

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    return [
      '#pre_render' => [
        [$this, 'convert'],
        [$this, 'preRenderComponent'],
      ],
      '#component' => '',
      '#props' => [],
      '#slots' => [],
      '#propsAlter' => [],
      '#slotsAlter' => [],
    ];
  }

  /**
   *
   */
  private static function resolveCompactFormat(array $element): array {
    foreach (Element::properties($element) as $property) {
      if (in_array($property, self::COMMON_RENDER_PROPERTIES)) {
        continue;
      }
      // @todo test if slot or prop, looking the component definition.
    }
    return $element;
  }

  /**
   *
   */
  public function convert(array $element): array {
    $element = self::resolveCompactFormat($element);
    $element["#type"] = "component";
    if (array_key_exists("#id", $element) && is_string($element["#id"])) {
      $element["#id"] = \Drupal::service('plugin.manager.sdc')->getNamespacedId($element["#id"]);
      $element["#component"] = $element["#id"];
      unset($element["#id"]);
    }
    if (array_key_exists("#fields", $element) && is_array($element["#fields"])) {
      $element["#slots"] = $element["#fields"];
      unset($element["#fields"]);
    }
    if (array_key_exists("#settings", $element) && is_array($element["#settings"])) {
      $element["#props"] = $element["#settings"];
      unset($element["#settings"]);
    }
    if (array_key_exists("#variant", $element) && is_string($element["#variant"])) {
      $element["#props"]["variant"] = $element["#variant"];
      unset($element["#variant"]);
    }
    // @todo Translate message
    \Drupal::logger('ui_patterns_legacy')->warning("Deprecated pattern render element or pattern Twig function: " . $element["#component"]);
    // @todo Remove before shipping
    $messenger = \Drupal::service('messenger');
    $messenger->addWarning("Deprecated pattern render element or pattern Twig function: " . $element["#component"]);
    return $element;
  }

}
