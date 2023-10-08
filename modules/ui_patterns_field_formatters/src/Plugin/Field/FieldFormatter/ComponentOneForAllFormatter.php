<?php

namespace Drupal\ui_patterns_field_formatters\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\ui_patterns\Form\UiPatternsFormBuilderTrait;

/**
 * Plugin implementation of the 'component_all' formatter.
 *
 * Field types are altered in
 * ui_patterns_field_formatters_field_formatter_info_alter().
 *
 * @FieldFormatter(
 *   id = "component_all",
 *   label = @Translation("Component (one for all)"),
 *   field_types = {
 *     "string"
 *   },
 * )
 */
class ComponentOneForAllFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  use UiPatternsFormBuilderTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $settings = array_merge(
      parent::defaultSettings(),
      self::getComponentFormDefault(),
    );
    // Temp.
    return $settings;

  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);
    $plugin_manager = \Drupal::service('plugin.manager.sdc');
    $component_id = "ui_patterns_test:card";
    $component = $plugin_manager->find($component_id);
    $context = [];
    $form['ui_patterns'] = $this->buildComponentForm($form_state, $component, $context);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $component_id = "ui_patterns_test:card";
    $variant_id = "";
    $slots = $this->getSetting('slots');
    $props = array_merge(
    $this->getSetting('props'),
    [
      "variant" => $variant_id,
    ]
    );
    return [
      '#type' => 'component',
      '#component' => $component_id,
      '#slots' => $slots,
      '#props' => $props,
    ];
  }

}
