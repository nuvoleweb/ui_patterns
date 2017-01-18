<?php

namespace Drupal\ui_patterns\Form;

use Drupal\Component\Utility\SortArray;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\ui_patterns\Plugin\UiPatternsSourceBase;

/**
 * Trait PatternDisplayFormTrait.
 *
 * @property \Drupal\ui_patterns\UiPatternsManager $patternsManager
 * @property \Drupal\ui_patterns\Plugin\UiPatternsSourceManager $sourceManager
 *
 * @package Drupal\ui_patterns\Form
 */
trait PatternDisplayFormTrait {

  use StringTranslationTrait;

  /**
   * Build pattern display form.
   *
   * @param array $form
   *    Form array.
   * @param string $tag
   *    Source field tag.
   * @param array $context
   *    Plugin context.
   * @param array $configuration
   *    Default configuration coming form the host form.
   */
  public function buildPatternDisplayForm(array &$form, $tag, array $context, array $configuration) {

    $form['pattern'] = [
      '#type' => 'select',
      '#empty_value' => '_none',
      '#title' => $this->t('Pattern'),
      '#options' => $this->patternsManager->getPatternsOptions(),
      '#required' => TRUE,
      '#attributes' => ['id' => 'patterns-select'],
    ];

    foreach ($this->patternsManager->getDefinitions() as $pattern_id => $definition) {
      $form['pattern_mapping'][$pattern_id] = [
        '#type' => 'container',
        '#states' => array(
          'visible' => [
            'select[id="patterns-select"]' => array('value' => $pattern_id),
          ],
        ),
      ];
      $form['pattern_mapping'][$pattern_id]['settings'] = $this->getMappingForm($pattern_id, $tag, $context);
    }
  }

  /**
   * Get mapping form.
   *
   * @param string $pattern_id
   *    Pattern ID for which to print the mapping form for.
   * @param string $tag
   *    Source field plugin tag.
   * @param array $context
   *    Plugin context.
   *
   * @return array
   *    Mapping form.
   */
  public function getMappingForm($pattern_id, $tag, array $context) {

    $elements = [
      '#type' => 'table',
      '#header' => [
        $this->t('Source'),
        $this->t('Plugin'),
        $this->t('Destination'),
        $this->t('Weight'),
      ],
    ];
    $elements['#tabledrag'][] = [
      'action' => 'order',
      'relationship' => 'sibling',
      'group' => 'field-weight',
    ];

    $destinations = ['_hidden' => $this->t('- Hidden -')] + $this->patternsManager->getPatternFieldsOptions($pattern_id);

    foreach ($this->sourceManager->getFieldsByTag($tag, $context) as $field_name => $field) {
      $elements[$field_name] = [
        'info' => [
          '#plain_text' => $field->getFieldLabel(),
        ],
        'plugin' => [
          '#plain_text' => $field->getPluginLabel(),
        ],
        'destination' => [
          '#type' => 'select',
          '#title' => $this->t('Destination for @field', ['@field' => $field->getFieldLabel()]),
          '#title_display' => 'invisible',
          '#default_value' => '_disabled',
          '#options' => $destinations,
        ],
        'weight' => [
          '#type' => 'weight',
          '#default_value' => 0,
          '#delta' => 20,
          '#title' => $this->t('Weight for @field field', array('@field' => $field->getFieldLabel())),
          '#title_display' => 'invisible',
          '#attributes' => [
            'class' => ['field-weight'],
          ],
        ],
        '#attributes' => [
          'class' => ['draggable'],
        ],
      ];
    }

    return $elements;
  }

  /**
   * Normalize settings coming from a form submission.
   *
   * @param array $settings
   *    Pattern display form values array.
   */
  static public function processFormStateValues(array &$settings) {
    // Normalize only when necessary.
    if (isset($settings['pattern_mapping'][$settings['pattern']]['settings'])) {
      $settings['pattern_mapping'] = $settings['pattern_mapping'][$settings['pattern']]['settings'];

      // Process fields and filter out the hidden ones.
      foreach ($settings['pattern_mapping'] as $key => $setting) {
        if ($setting['destination'] == '_hidden') {
          unset($settings['pattern_mapping'][$key]);
        }
        else {
          list($plugin, $source) = explode(UiPatternsSourceBase::DERIVATIVE_SEPARATOR, $key);
          $settings['pattern_mapping'][$key]['plugin'] = $plugin;
          $settings['pattern_mapping'][$key]['source'] = $source;
        }
      }

      // Normalize weights.
      $weight = 0;
      uasort($settings['pattern_mapping'], array(SortArray::class, 'sortByWeightElement'));
      foreach ($settings['pattern_mapping'] as $key => $setting) {
        $settings['pattern_mapping'][$key]['weight'] = $weight++;
      }
    }
  }

}
