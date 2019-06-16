<?php

namespace Drupal\ui_patterns\Form;

use Drupal\Component\Utility\SortArray;
use Drupal\ui_patterns\Plugin\PatternSourceBase;

/**
 * Trait PatternDisplayFormTrait.
 *
 * @property \Drupal\ui_patterns\UiPatternsManager $patternsManager
 * @property \Drupal\ui_patterns\UiPatternsSourceManager $sourceManager
 * @property \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
 * @method \Drupal\Core\StringTranslation\TranslatableMarkup t($string, array $args = [], array $options = [])
 *
 * @package Drupal\ui_patterns\Form
 */
trait PatternDisplayFormTrait {

  /**
   * Build pattern display form.
   *
   * @param array $form
   *   Form array.
   * @param string $tag
   *   Source field tag.
   * @param array $context
   *   Plugin context.
   * @param array $configuration
   *   Default configuration coming form the host form.
   */
  public function buildPatternDisplayForm(array &$form, $tag, array $context, array $configuration) {

    $form['pattern'] = [
      '#type' => 'select',
      '#empty_value' => '_none',
      '#title' => $this->t('Pattern'),
      '#options' => $this->patternsManager->getPatternsOptions(),
      '#default_value' => isset($configuration['pattern']) ? $configuration['pattern'] : NULL,
      '#required' => TRUE,
      '#attributes' => ['id' => 'patterns-select'],
    ];
    $form['variants'] = ['#type' => 'container'];

    /** @var \Drupal\ui_patterns\Definition\PatternDefinition $definition */
    foreach ($this->patternsManager->getDefinitions() as $pattern_id => $definition) {
      if ($definition->hasVariants()) {
        $form['variants'][$pattern_id] = [
          '#type' => 'select',
          '#title' => $this->t('Variant'),
          '#options' => $definition->getVariantsAsOptions(),
          '#default_value' => isset($configuration['pattern_variant']) ? $configuration['pattern_variant'] : NULL,
          '#weight' => 0,
          '#states' => [
            'visible' => [
              'select[id="patterns-select"]' => ['value' => $pattern_id],
            ],
          ],
        ];
      }
      $form['pattern_mapping'][$pattern_id] = [
        '#type' => 'container',
        '#weight' => 1,
        '#states' => [
          'visible' => [
            'select[id="patterns-select"]' => ['value' => $pattern_id],
          ],
        ],
        'settings' => $this->getMappingForm($pattern_id, $tag, $context, $configuration),
      ];
    }

    $this->moduleHandler->alter('ui_patterns_display_settings_form', $form, $configuration);
  }

  /**
   * Get mapping form.
   *
   * @param string $pattern_id
   *   Pattern ID for which to print the mapping form for.
   * @param string $tag
   *   Source field plugin tag.
   * @param array $context
   *   Plugin context.
   * @param array $configuration
   *   Default configuration coming form the host form.
   *
   * @return array
   *   Mapping form.
   */
  public function getMappingForm($pattern_id, $tag, array $context, array $configuration) {
    /** @var \Drupal\ui_patterns\Definition\PatternDefinition $pattern */
    $pattern = $this->patternsManager->getDefinition($pattern_id);

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

    $destinations = ['_hidden' => $this->t('- Hidden -')] + $pattern->getFieldsAsOptions();

    $fields = [];
    foreach ($this->sourceManager->getFieldsByTag($tag, $context) as $field_name => $field) {
      $weight = (int) $this->getDefaultValue($configuration, $field_name, 'weight');
      $fields[$field_name] = [
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
          '#default_value' => $this->getDefaultValue($configuration, $field_name, 'destination'),
          '#options' => $destinations,
        ],
        'weight' => [
          '#type' => 'weight',
          '#default_value' => $weight,
          '#delta' => 20,
          '#title' => $this->t('Weight for @field field', ['@field' => $field->getFieldLabel()]),
          '#title_display' => 'invisible',
          '#attributes' => [
            'class' => ['field-weight'],
          ],
        ],
        '#attributes' => [
          'class' => ['draggable'],
        ],
        '#weight' => $weight,
      ];
    }

    uasort($fields, [SortArray::class, 'sortByWeightProperty']);
    return array_merge($elements, $fields);
  }

  /**
   * Normalize settings coming from a form submission.
   *
   * @param array $settings
   *   Pattern display form values array.
   */
  public static function processFormStateValues(array &$settings) {
    if (isset($settings['variants']) && isset($settings['variants'][$settings['pattern']])) {
      $settings['pattern_variant'] = $settings['variants'][$settings['pattern']];
      unset($settings['variants']);
    }

    // Normalize only when necessary.
    if (isset($settings['pattern_mapping'][$settings['pattern']]['settings'])) {
      $settings['pattern_mapping'] = $settings['pattern_mapping'][$settings['pattern']]['settings'];

      // Process fields and filter out the hidden ones.
      foreach ($settings['pattern_mapping'] as $key => $setting) {
        if ($setting['destination'] == '_hidden') {
          unset($settings['pattern_mapping'][$key]);
        }
        else {
          list($plugin, $source) = explode(PatternSourceBase::DERIVATIVE_SEPARATOR, $key, 2);
          $settings['pattern_mapping'][$key]['plugin'] = $plugin;
          $settings['pattern_mapping'][$key]['source'] = $source;
        }
      }

      // Normalize weights.
      $weight = 0;
      uasort($settings['pattern_mapping'], [SortArray::class, 'sortByWeightElement']);
      foreach ($settings['pattern_mapping'] as $key => $setting) {
        $settings['pattern_mapping'][$key]['weight'] = $weight++;
      }
    }
  }

  /**
   * Helper function: return mapping destination given plugin id and field name.
   *
   * @param string $plugin
   *   Current plugin ID.
   * @param string $source
   *   Source field name.
   * @param array $settings
   *   Setting array.
   *
   * @return string|null
   *   Destination field or NULL if none found.
   */
  public function getMappingDestination($plugin, $source, array $settings) {
    $mapping_id = $plugin . PatternSourceBase::DERIVATIVE_SEPARATOR . $source;
    if (isset($settings['pattern_mapping'][$mapping_id])) {
      return $settings['pattern_mapping'][$mapping_id]['destination'];
    }
    return NULL;
  }

  /**
   * Helper function: check if given source field has mapping destination.
   *
   * @param string $plugin
   *   Current plugin ID.
   * @param string $source
   *   Source field name.
   * @param array $settings
   *   Setting array.
   *
   * @return bool
   *   TRUE if source has destination field, FALSE otherwise.
   */
  public function hasMappingDestination($plugin, $source, array $settings) {
    return $this->getMappingDestination($plugin, $source, $settings) !== NULL;
  }

  /**
   * Helper function: get default value.
   *
   * @param array $configuration
   *   Configuration.
   * @param string $field_name
   *   Field name.
   * @param string $value
   *   Value name.
   *
   * @return string
   *   Field property value.
   */
  protected function getDefaultValue(array $configuration, $field_name, $value) {
    if (isset($configuration['pattern_mapping'][$field_name][$value])) {
      return $configuration['pattern_mapping'][$field_name][$value];
    }
    return NULL;
  }

}
