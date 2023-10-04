<?php

namespace Drupal\ui_patterns_legacy\Element;

use Drupal\Core\Render\Element;
use Drupal\sdc\Element\ComponentElement;

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
  private static function getComponentNamespace(array $element): array {
    if (!array_key_exists("#id", $element)) {
      // Nothing to do.
      return $element;
    }
    $parts = explode(":", $element["id"]);
    if (count(array_filter($parts)) === 2) {
      // Already namespaced.
      return $element;
    }
    if (count(array_filter($parts)) > 2) {
      // Unexpected situation.
      return $element;
    }
    $components = \Drupal::service('plugin.manager.sdc')->getAllComponents();
    // @todo Search first in current active theme, then parents themes, then modules.
    foreach ($components as $component) {
      if ($component->getPluginDefinition()["machineName"] === $element["#id"]) {
        $element["#id"] = $component->getPluginId();
        return $element;
      }
    }
    return $element;
  }

  /**
   *
   */
  public function convert(array $element): array {
    $element = self::resolveCompactFormat($element);
    $element = self::getComponentNamespace($element);
    $element["#type"] = "component";
    $element["#component"] = $element["#id"];
    unset($element["#id"]);
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
