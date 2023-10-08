<?php

namespace Drupal\ui_patterns\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\sdc\Component\ComponentMetadata;

/**
 *
 */
trait UiPatternsFormBuilderTrait {

  /**
   * Build component fieldset.
   *
   * @param Drupal\sdc\Component\ComponentMetadata $component
   *   The pattern definition.
   */
  protected function buildComponentForm(FormStateInterface $form_state, ComponentMetadata $component_metadata, array $context): array {
    return [
      $this->buildVariantSelectorForm($form_state, $component_metadata),
      $this->buildSlotsForm($form_state, $component_metadata, $context),
      $this->buildPropsForm($form_state, $component_metadata, $context),
    ];
  }

  /**
   *
   */
  protected function buildVariantSelectorForm(FormStateInterface $form_state, ComponentMetadata $component_metadata): array {
    return [];
  }

  /**
   *
   */
  protected function buildSlotsForm(FormStateInterface $form_state, ComponentMetadata $component_metadata, array $context): array {
    return [];
  }

  /**
   *
   */
  protected function buildPropsForm(FormStateInterface $form_state, ComponentMetadata $component_metadata, array $context): array {
    $sub_sources_form_value = $context['form_values'];
    $form = [];
    $sub_sources = [];
    foreach ($component_metadata->schema['properties'] as $prop_id => $prop) {
      $sources = $prop['ui_patterns']['source'];
      if (count($sources) > 0) {
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

  /**
   * Build components selector widget.
   */
  protected function buildComponentsForm(): array {
    return [];
  }

}
