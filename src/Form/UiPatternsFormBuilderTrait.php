<?php
namespace Drupal\ui_patterns\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\sdc\Component\ComponentMetadata;

trait UiPatternsFormBuilderTrait {

  /**
   * Build component fieldset.
   *
   * @param Drupal\sdc\Component\ComponentMetadata $component
   *   The pattern definition.
   */
  protected function buildComponentForm(FormStateInterface $form_state, ComponentMetadata $component, array $context):array {
    /** @var \Drupal\ui_patterns\SourcePluginManager $source_provider_manager */
    $source_manager = \Drupal::service('plugin.manager.ui_patterns_source');
    $sources = $source_manager->getSources($component);
    return ['#type' => 'textfield', '#title' => 'Dummy'];
  }

  /**
   * Build components selector widget.
   *
   */
  protected function buildComponentsForm():array {
    return [];
  }

}
