<?php

namespace Drupal\ui_patterns\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\sdc\Plugin\Component;

/**
 *
 */
trait UiPatternsFormBuilderTrait {

  /**
   * ...
   *
   * To use with:
   * - PluginSettingsInterface::defaultSettings
   * - ConfigurableInterface::defaultConfiguration
   * - views/PluginBase::setOptionDefaults
   * - ...
   */
  public static function getComponentFormDefault() {
    return [
      "component_id" => "",
      "variant_id" => "",
      "slots" => [],
      "props" => [],
    ];
  }

  /**
   * Build the complete form.
   *
   * @param Drupal\sdc\Plugin\Component $component
   *   The component plugin.
   */
  protected function buildComponentForm(FormStateInterface $form_state, Component $component, array $context): array {
    return [
      "component_id" => $this->buildComponentSelectorForm(),
      "variant_id" => $this->buildVariantSelectorForm($form_state, $component),
      "slots" => $this->buildSlotsForm($form_state, $component, $context),
      "forms" => $this->buildPropsForm($form_state, $component, $context),
    ];
  }

  /**
   * Build components selector widget.
   */
  protected function buildComponentSelectorForm(): array {
    $components = \Drupal::service("plugin.manager.sdc")->getDefinitions();
    // @todo getGroupedDefinitions?
    $options = [];
    foreach ($components as $component_id => $component) {
      $options[$component_id] = $component["name"];
    }
    return [
      "#type" => "select",
      "#title" => t("Component"),
      "#options" => $options,
    ];
  }

  /**
   *
   */
  protected function buildVariantSelectorForm(FormStateInterface $form_state, Component $component): array {
    $definition = $component->getPluginDefinition();
    if (!isset($definition["variants"])) {
      return [];
    }
    $options = [];
    foreach ($definition["variants"] as $variant_id => $variant) {
      $options[$variant_id] = $variant["title"];
    }
    return [
      "#type" => "select",
      "#title" => t("Variant"),
      "#options" => $options,
    ];
  }

  /**
   *
   */
  protected function buildSlotsForm(FormStateInterface $form_state, Component $component, array $context): array {
    return [];
  }

  /**
   *
   */
  protected function buildPropsForm(FormStateInterface $form_state, Component $component, array $context): array {
    $sub_sources_form_value = $context['form_values'];
    $form = [];
    $sub_sources = [];
    foreach ($component->metadata->schema['properties'] as $prop_id => $prop) {
      $sources = $prop['ui_patterns']['source'];
      if (count($sources) == 1) {
        /** @var \Drupal\ui_patterns\SourcePluginBase $default_source */
        $default_source = current($sources);
        $configuration = $default_source->getConfiguration();
        if (isset($sub_sources_form_value[$prop_id])) {
          $configuration['form_value'] = $sub_sources_form_value[$prop_id];
          $default_source->setConfiguration($configuration);
        }
        $form[$prop_id] = $default_source->buildConfigurationForm($form, $form_state);
        $sub_sources[$prop_id] = $default_source;
      }
      elseif (count($sources) > 1) {
        $options = [];
        foreach ($sources as $source) {
          $options[$source->getPluginId()] = $source->label();
        }
        $form[$prop_id] = [
          "#type" => "select",
          "#title" => $prop["title"],
          "#options" => $options,
        ];
        // @todo dynamically load source form on select.
        // @todo $sub_sources?
      }
    }
    if (!$form_state->has('sub_sources')) {
      $form_state->set('sub_sources', $sub_sources);
    }
    return $form;
  }

  /**
   *
   */
  protected function submitComponentForm($form, FormStateInterface $form_state, array $context):array {
    $sub_sources = $form_state->get('sub_sources');
    $sub_values = [];
    foreach ($sub_sources as $prop_id => $sub_source) {
      $sub_source->submitConfigurationForm($form['ui_patterns'][$prop_id], $form_state);
      $sub_values[$prop_id] = $sub_source->getConfiguration()['form_value'];
    }
    return $sub_values;
  }

}
