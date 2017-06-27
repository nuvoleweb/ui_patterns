<?php

namespace Drupal\ui_patterns_variants\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\ui_patterns_layouts\Plugin\Layout\PatternLayout;

/**
 * Class PatternVariants.
 *
 * Extends and replaces the PatternLayout class in order to provide variant
 * functionality to all patterns.
 *
 * @package Drupal\ui_patterns_variants\Plugin\Layout
 */
class PatternVariants extends PatternLayout {

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {

    // Get the normal config from the parent.
    $config = parent::getConfiguration();

    // If the pattern definition has variants add it to the config.
    if (!isset($config['pattern']['variants'])) {
      $config['pattern']['variants'] = [];
    }

    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    // Add the variant configuration fields.
    $form = parent::buildConfigurationForm($form, $form_state);
    $pattern = $this->getPluginDefinition()->get('additional')['pattern'];
    $variants = _ui_patterns_variants_get_variants($pattern);

    if (empty($variants)) {
      return $form;
    }

    $config = $this->getConfiguration();
    $defaults = [];
    if (isset($config['pattern']['variants'])) {
      $defaults = $config['pattern']['variants'];
    }

    $field_options = [];

    /* @var \Drupal\Core\Entity\EntityFormInterface $entity_form */
    $entity_form = $form_state->getFormObject();

    if (method_exists($entity_form, 'getEntity') && $entity_form->getEntity()) {
      $fieldDefinitions = $entity_form->getEntity()->get('fieldDefinitions');
    }
    else {
      // Panels form.
      list($entity_type, $bundle) = explode('__', $entity_form->getMachineName());
      /** @var \Drupal\Core\Entity\EntityFieldManager $entity_manager */
      $entity_manager = \Drupal::service('entity_field.manager');
      $fieldDefinitions = $entity_manager->getFieldDefinitions($entity_type, $bundle);
    }

    /** @var \Drupal\field\Entity\FieldConfig $definition */
    foreach ($fieldDefinitions as $field_name => $definition) {
      if ($definition->getFieldStorageDefinition()->isBaseField() == FALSE) {
        $field_options[$field_name] = $definition->getLabel();
      }
    }

    $form['pattern'] += _ui_patterns_variants_get_form_elements($variants, $defaults, $field_options);
    return $form;
  }

}
