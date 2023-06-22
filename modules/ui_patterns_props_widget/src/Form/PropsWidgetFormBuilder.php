<?php

namespace Drupal\ui_patterns_props_widget\Form;

use Drupal\Core\Entity\ContentEntityType;
use Drupal\sdc\Component\ComponentMetadata;
use Drupal\ui_patterns_props_widget\UiPatternsPropsWidget;
use Drupal\ui_patterns_props_widget\UiPatternsPropsWidgetManager;

/**
 * Build prop in manage display form.
 */
class PropsWidgetFormBuilder {

  /**
   * Build a hidden tree link token for performance reasons.
   *
   * Forms with class .js-ui-patterns-props-widget-show-token-link will
   * generate Browse token button which click on the hidden link.
   * This will reduce the number of browse buttons.
   *
   * @param $form
   *   The form.
   */
  private static function buildTokenLink(&$form) {
    $content_entity_types = [];
    $entity_type_definations = \Drupal::entityTypeManager()->getDefinitions();
    /** @var EntityTypeInterface $definition */
    foreach ($entity_type_definations as $definition) {
      if ($definition instanceof ContentEntityType) {
        $content_entity_types[] = $definition->id();
      }
    }
    $form['token_link'] = [
      '#prefix' => '<div id="ui-patterns-props-widget-token-link">',
      '#suffix' => '</div>',
      '#theme' => 'token_tree_link',
      '#token_types' => $content_entity_types,
      '#show_restricted' => TRUE,
      '#weight' => 90,
    ];
  }

  /**
   * Build pattern props widget fieldset.
   *
   * @param array $form
   *   Form array.
   * @param \Drupal\sdc\Component\ComponentMetadata $component_metadata
   *   The pattern definition.
   * @param array $configuration
   *   The pattern configuration.
   */
  public static function layoutForm(array &$form, ComponentMetadata $component_metadata, array $configuration) {
    $widgets = UiPatternsPropsWidget::getPatternDefinitionWidgets($component_metadata);
    self::buildTokenLink($form);

    $form['#attached']['library'][] = 'ui_patterns_props_widget/widget';
    if (UiPatternsPropsWidgetManager::allowVariantToken($component_metadata)) {
      $variant_token_value = $configuration['pattern']['variant_token'] ?? NULL;
      $form['variant_token'] = [
        '#type' => 'textfield',
        '#title' => 'Variant token',
        '#attributes' => ['class' => ['js-ui-patterns-props-widget-show-token-link']],
        '#default_value' => $variant_token_value,
      ];
    }

    $form['variant']['#attributes']['class'][] = 'ui-patterns-variant-selector-' . $component_metadata->id;
    if (!empty($widgets)) {
      foreach ($widgets as $key => $widget) {
        if (empty($widget->getType()) || !$widget->isFormVisible()) {
          continue;
        }

        if (!isset($form['settings'])) {
          $form['settings'] = [
            '#type' => 'fieldset',
            '#title' => t('Settings'),
          ];
        }
        $setting_value = $configuration['pattern']['settings'][$key] ?? NULL;
        $token_value = $configuration['pattern']['settings'][$key . "_token"] ?? "";
        $widget = UiPatternsPropsWidget::createWidget($component_metadata, $widget);
        $form['settings'] += $widget->buildConfigurationForm([], $setting_value, $token_value, 'layouts_display');
      }
      PropsWidgetFormBuilder::buildVariantsForm(".ui-patterns-variant-selector-" . $component_metadata->id, $form['settings'], $component_metadata);
    }
  }

  /**
   * Build widget display form.
   *
   * @param array $form
   *   Form array.
   * @param array $configuration
   *   Configurations array.
   */
  public static function displayForm(array &$form, array $configuration) {
    $form['#attached']['library'][] = 'ui_patterns_props_widget/widget';
    self::buildTokenLink($form);

    /** @var \Drupal\sdc\ComponentPluginManager $plugin_manager */
    $plugin_manager = \Drupal::service('plugin.manager.sdc');
    /** @var \Drupal\sdc\Component\ComponentMetadata[] $components */
    $components = $plugin_manager->getDefinitions();

    foreach ($components as $component_id => $component) {
      $widgets = UiPatternsPropsWidget::getPatternDefinitionWidgets($component);
      $form['variants'][$component_id]['#attributes']['class'][] = 'ui-patterns-variant-selector-' . $component_id;
      if (UiPatternsPropsWidgetManager::allowVariantToken($component)) {
        $variant_token_value = $configuration['variants_token'][$component_id] ?? NULL;
        $form['variants']['#weight'] = 20;
        $form['pattern_mapping']['#weight'] = 30;
        $form['pattern_settings']['#weight'] = 40;
        $form['variants_token'] = [
          '#type' => 'container',
          '#title' => t('Pattern Variant'),
          '#weight' => 25,
          '#states' => [
            'visible' => [
              'select[id="patterns-select"]' => ['value' => $component_id],
            ],
          ],
        ];
        $form['variants_token'][$component_id] = [
          '#type' => 'textfield',
          '#title' => t('Variant token'),
          '#default_value' => $variant_token_value,
          '#attributes' => ['class' => ['js-ui-patterns-props-widget-show-token-link']],
          '#states' => [
            'visible' => [
              'select[id="patterns-select"]' => ['value' => $component_id],
            ],
          ],
        ];
      }
      if (!empty($widgets)) {
        foreach ($widgets as $key => $widget) {
          if (empty($widget->getType()) || !$widget->isFormVisible()) {
            continue;
          }
          if (!isset($form['pattern_widgets'][$component_id])) {
            $form['pattern_widgets'][$component_id] = [
              '#type' => 'fieldset',
              '#title' => t('Settings'),
              '#states' => [
                'visible' => [
                  'select[id="patterns-select"]' => ['value' => $component_id],
                ],
              ],
            ];
          }
          $fieldset = &$form['pattern_settings'][$component_id];
          $settingType = UiPatternsPropsWidget::createWidget($component, $widget);
          $setting_value = $configuration['pattern_settings'][$component_id][$key] ?? NULL;
          $token_value = $configuration['pattern_settings'][$component_id][$key . "_token"] ?? NULL;
          $fieldset += $settingType->buildConfigurationForm([], $setting_value, $token_value, 'display');
        }
        PropsWidgetFormBuilder::buildVariantsForm('.ui-patterns-variant-selector-' . $component_id, $fieldset, $component);
      }
    }
  }

  /**
   * Hide all settings which are configured by the variant.
   *
   * @param string $select_selector
   *   The id of the variant select field.
   * @param array $fieldset
   *   The fieldset.
   * @param \Drupal\sdc\Component\ComponentMetadata $component_metadata
   *   The pattern definition.
   */
  private static function buildVariantsForm($select_selector, array &$fieldset, ComponentMetadata $component_metadata) {
    $variants = $component_metadata->variants ?? [];
    foreach ($variants as $variant_ary) {
      $settings = $variant_ary['settings'] ?? [];
      foreach ($settings as $name => $setting) {
        if (isset($fieldset[$name])) {
          // Add an or before a new state begins.
          if (isset($fieldset[$name]['#states']['invisible']) && count($fieldset[$name]['#states']['invisible']) != 0) {
            $fieldset[$name]['#states']['invisible'][] = 'or';
          }
          // Hide configured setting.
          $fieldset[$name]['#states']['invisible'][][$select_selector]['value'] = $variant->getName();
          $fieldset[$name . '_token']['#states']['invisible'][][$select_selector]['value'] = $variant->getName();
        }
      }
    }
  }

}
