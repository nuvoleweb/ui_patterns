<?php

namespace Drupal\ui_patterns_layouts\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Layout\LayoutDefinition;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\ui_patterns\UiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LayoutDefault.
 *
 * @package Drupal\ui_patterns_layouts\Plugin\Layout
 */
class PatternLayout extends LayoutDefault implements PluginFormInterface, ContainerFactoryPluginInterface {

  /**
   * Pattern manager service.
   *
   * @var \Drupal\ui_patterns\UiPatternsManager
   */
  protected $patternManager = NULL;

  /**
   * Constructs a LocalActionDefault object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param \Drupal\Core\Layout\LayoutDefinition $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\ui_patterns\UiPatternsManager $pattern_manager
   *    Pattern manager service.
   */
  public function __construct(array $configuration, $plugin_id, LayoutDefinition $plugin_definition, UiPatternsManager $pattern_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->patternManager = $pattern_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.ui_patterns')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $configuration = $this->getConfiguration();

    // Remove default field template if "Only content" option has been selected.
    if ($configuration['pattern']['field_templates'] == 'only_content') {
      $this->processOnlyContentFields($regions);
    }

    // Patterns expect regions to be passed along in a render array fashion.
    $fields = [];
    foreach ($regions as $region_name => $region) {
      $fields[$region_name] = $regions[$region_name];
    }

    return [
      '#type' => 'pattern',
      '#id' => $this->getPluginDefinition()->get('additional')['pattern'],
      '#fields' => $fields,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'pattern' => [
        'field_templates' => 'default',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $configuration = $this->getConfiguration();
    $form = [];

    $form['pattern'] = [
      '#group' => 'additional_settings',
      '#type' => 'details',
      '#title' => $this->t('Pattern settings'),
      '#tree' => TRUE,
    ];
    $form['pattern']['field_templates'] = [
      '#type' => 'select',
      '#title' => $this->t('Field templates'),
      '#options' => [
        'default' => $this->t("Default"),
        'only_content' => $this->t("Only content"),
      ],
      '#description' => implode('<br/>', [
        $this->t("<b>Default</b>: use field templates to wrap field content."),
        $this->t("<b>Only content</b>: only print field content, without field wrapping or label."),
      ]),
      '#default_value' => $configuration['pattern']['field_templates'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration = $form_state->getValues();
  }

  /**
   * Remove default field template if "Only content" option has been selected.
   *
   * @param array $regions
   *    Layout regions.
   */
  protected function processOnlyContentFields(array &$regions) {
    foreach ($regions as $region_name => $region) {
      if (is_array($region)) {
        foreach ($regions[$region_name] as $field_name => $field) {
          if (is_array($field) && isset($field['#theme']) && $field['#theme'] == 'field') {
            $regions[$region_name][$field_name]['#theme'] = NULL;
          }
        }
      }
    }
  }

}
