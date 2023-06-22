<?php

namespace Drupal\ui_patterns_props_widget\Element;

use Drupal\Core\Security\TrustedCallbackInterface;
use Drupal\Core\Template\Attribute;
use Drupal\ui_patterns_props_widget\UiPatternsPropsWidget;

/**
 * Renders a pattern element.
 */
class ComponentPropsWidget implements TrustedCallbackInterface {

  /**
   * Process prop widget.
   *
   * @param array $element
   *   Render array.
   * @param bool $preview
   *   True when called in pattern preview mode.
   *
   * @return array
   *   Render array.
   */
  public static function processPropsWidget(array $element, $preview = FALSE) {

    /** @var \Drupal\sdc\ComponentPluginManager $sdc_plugin_manager */
    $sdc_plugin_manager = \Drupal::service('plugin.manager.sdc');
    $entity = $element['#entity'] ?? NULL;
    $props_configuration = $element['#props_configuration'] ?? [];
    $component_metadata = $sdc_plugin_manager->find($element['#component'])->metadata;
    $variant = $element['#variant'] ?? NULL;
    $processed_props = UiPatternsPropsWidget::preprocess($component_metadata, $props_configuration, $variant, $preview, $entity);
    unset($element['#props_configuration']);
    foreach ($processed_props as $name => $prop_value) {
      if (!isset($element['#props'][$name])) {
        $element['#props'][$name] = $prop_value;
      }
      else {
        if ($prop_value instanceof Attribute && $element['#props'][$name] instanceof Attribute) {
          $element['#props'][$name] = new Attribute(array_merge($prop_value->toArray(), $element['#props'][$name]->toArray()));
        }
        elseif (is_array($element['#props'][$name]) && is_array($prop_value)) {
          $element['#props'][$name] = array_merge($element['#props'][$name], $prop_value);
        }
      }
    }
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks() {
    return ['processPropsWidget'];
  }

}
