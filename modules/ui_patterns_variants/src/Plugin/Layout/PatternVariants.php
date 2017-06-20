<?php

namespace Drupal\ui_patterns_variants\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\ui_patterns_layouts\Plugin\Layout\PatternLayout;

/**
 * Class PatternVariants.
 *
 * @package Drupal\ui_patterns_variants\Plugin\Layout
 */
class PatternVariants extends PatternLayout {

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    $config = parent::getConfiguration();
    if (!isset($config['pattern']['variants'])) {
      $config['pattern']['variants'] = [];
    }
    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $pattern = $this->getPluginDefinition()->get('additional')['pattern'];
    if (empty($variants = _ui_patterns_variants_get_variants($pattern))) {
      return $form;
    }

    /* @var \Drupal\Core\Entity\EntityFormInterface $entity_form */
    $entity_form = $form_state->getFormObject();
    if (!method_exists($entity_form, 'getEntity')) {
      // todo: Panels patterns.
      return $form;
    }
    $fieldDefinitions = $entity_form->getEntity()->get('fieldDefinitions');

    $field_options = [];
    /** @var \Drupal\field\Entity\FieldConfig $definition */
    foreach ($fieldDefinitions as $field_name => $definition) {
      $field_options[$field_name] = $definition->getLabel();
    }

    $config = $this->getConfiguration();
    $defaults = [];
    if (isset($config['pattern']['variants'])) {
      $defaults = $config['pattern']['variants'];
    }

    $form['pattern'] += _ui_patterns_variants_get_form_elements($variants, $defaults, $field_options);
    return $form;
  }

}
