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
    $fieldDefinitions = $entity_form->getEntity()->get('fieldDefinitions');

    $field_options = [];
    /** @var \Drupal\field\Entity\FieldConfig $definition */
    foreach ($fieldDefinitions as $field_name => $definition) {
      $field_options[$field_name] = $definition->getLabel();
    }

    $config = $this->getConfiguration();

    $form['pattern']['variants'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Variants'),
    ];

    foreach ($variants as $key => $variant) {

      if (!isset($config['pattern']['variants'][$key])) {
        $config['pattern']['variants'][$key] = [
          'default' => isset($variant['options']) ? key($variant['options']) : '',
          'variant_field' => 0,
          'options' => [],
        ];
      }

      $form['pattern']['variants'][$key] = [
        '#type' => 'fieldset',
        '#title' => $variant['label'],
        '#description' => $variant['description'],
      ];

      // If no variant options are defined, we must rely on field mapping.
      if (isset($variant['options'])) {
        $form['pattern']['variants'][$key]['default'] = [
          '#type' => 'select',
          '#title' => $this->t('Default value'),
          '#options' => $variant['options'],
          '#default_value' => $config['pattern']['variants'][$key]['default'],
        ];

        $form['pattern']['variants'][$key]['options'] = [
          '#type' => 'hidden',
          '#value' => $variant['options'],
        ];
      }

      $form['pattern']['variants'][$key]['variant_field'] = [
        '#type' => 'select',
        '#title' => $this->t('Variant Field'),
        '#description' => $this->t('Field to define variant to use.'),
        '#options' => $field_options,
        '#empty_option' => $this->t('- None -'),
        '#default_value' => $config['pattern']['variants'][$key]['variant_field'],
      ];
    }

    return $form;
  }

}
