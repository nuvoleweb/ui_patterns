<?php

namespace Drupal\ui_patterns_props_widget;

use Drupal\Core\Entity\EntityInterface;
use Drupal\sdc\Component\ComponentMetadata;
use Drupal\ui_patterns_props_widget\Definition\PropWidgetDefinition;

/**
 * UI Patterns props widget factory class.
 *
 * @package Drupal\ui_patterns_props_widget
 */
class UiPatternsPropsWidget {

  /**
   * Get pattern manager setting instance.
   *
   * @return \Drupal\ui_patterns_props_widget\UiPatternsPropsWidgetManager
   *   UI Patterns props widget manager.
   */
  public static function getManager() {
    return \Drupal::service('plugin.manager.ui_patterns_props_widget_manager');
  }

  /**
   * Preprocess props.
   *
   * @param \Drupal\sdc\Component\ComponentMetadata $component_metadata
   *   Pattern ID for which to preprocess.
   * @param array $props_configuration
   *   The stored props configuration.
   * @param string $variant
   *   The variant.
   * @param bool $preview
   *   Is preview.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity of the pattern. Useful for dynamic settings.
   *
   * @return array
   *   The processed settings.
   */
  public static function preprocess(ComponentMetadata $component_metadata, array $props_configuration, $variant, $preview, EntityInterface $entity = NULL) {
    $processed_widgets_data = [];
    $context = [];
    $context['entity'] = $entity;
    $widgets_definition = UiPatternsPropsWidget::getPatternDefinitionWidgets($component_metadata);
    foreach ($widgets_definition as $key => $widget_definition) {
      if ($widget_definition->getForcedValue()) {
        $value = $widget_definition->getForcedValue();
      }
      elseif (!empty($props_configuration[$key . '_token'])) {
        $token_value = $props_configuration[$key . '_token'];
        $token_data = [];
        if ($entity !== NULL) {
          $token_data[$entity->getEntityTypeId()] = $entity;
        }
        $value = \Drupal::token()->replace($token_value, $token_data, ['clear' => TRUE]);
      }
      elseif (isset($props_configuration[$key])) {
        $value = $props_configuration[$key];
      }
      elseif ($preview && !empty($widget_definition->getPreview())) {
        $value = $widget_definition->getPreview();
      }
      else {
        $value = $widget_definition->getDefaultValue();
      }
      if ($variant != 'default' && $variant != NULL) {
        $variant_ob = NULL;
        if ($variant_ob != NULL) {
          $variant_ary = $variant_ob->toArray();
          if (isset($variant_ary['settings']) && isset($variant_ary['settings'][$key])) {
            $value = $variant_ary['settings'][$key];
          }
        }
      }
      $widget = UiPatternsPropsWidget::createWidget($component_metadata, $widget_definition);
      $processed_widgets_data[$key] = $widget->preprocess($value, $context);
    }
    return $processed_widgets_data;

  }

  /**
   * Get setting definitions for a pattern definition.
   *
   * @param \Drupal\sdc\Component\ComponentMetadata $component_metadata
   *   The definition.
   *
   * @return \Drupal\ui_patterns_props_widget\Definition\PropWidgetDefinition[]
   *   Setting pattern definitons.
   */
  public static function getPatternDefinitionWidgets(ComponentMetadata $component_metadata) {
    $props = $component_metadata->schema['properties'];
    $widgets = [];
    if (!empty($props)) {
      foreach ($props as $key => $prop) {
        $def = ['label' => $prop['title'], 'options' => $prop['enum'] ?? NULL];
        $widget = UiPatternsPropsWidget::getManager()->getWidgetByProp($component_metadata, $key, $prop);
        if ($widget !== NULL) {
          $def['type'] = $widget['id'];
          $widgets[$key] = new PropWidgetDefinition($key, $def);
        }
      }
    }
    return $widgets;
  }

  /**
   * Create prop widget type plugin.
   *
   * @param \Drupal\ui_patterns_props_widget\Definition\PropWidgetDefinition $widget_defintion
   *   The widget defintion.
   *
   * @return \Drupal\ui_patterns_props_widget\Definition\PropWidgetDefinition
   *   Widget Plugin instance.
   */
  public static function createWidget(ComponentMetadata $component_metadata, PropWidgetDefinition $widget_defintion) {
    $configuration = [];
    $configuration['prop_widget_definition'] = $widget_defintion;
    $configuration['component_metadata'] = $component_metadata;
    return \Drupal::service('plugin.manager.ui_patterns_props_widget_manager')
      ->createInstance($widget_defintion->getType(), $configuration);
  }

}
