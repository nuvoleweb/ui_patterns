<?php

namespace Drupal\ui_patterns\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\TypedDataManager;
use Drupal\ui_patterns\UiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for UI Patterns Source plugins.
 */
abstract class UiPatternsSourceBase extends PluginBase implements UiPatternsSourceInterface {

  /**
   * Patterns manager service.
   *
   * @var \Drupal\ui_patterns\UiPatternsManager
   */
  protected $patternsManager;

  /**
   * Typed data manager service.
   *
   * @var \Drupal\Core\TypedData\TypedDataManager
   */
  protected $typedDataManager;

  /**
   * Definition of "Source field" typed data.
   *
   * @var \Drupal\ui_patterns\Plugin\DataType\SourceFieldDefinition
   */
  protected $sourceFieldDefinition;

  /**
   * UiPatternsSourceBase constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\ui_patterns\UiPatternsManager $ui_patterns_manager
   *    UI Patterns manager.
   * @param \Drupal\Core\TypedData\TypedDataManager $typed_data_manager
   *    Typed data manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UiPatternsManager $ui_patterns_manager, TypedDataManager $typed_data_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->patternsManager = $ui_patterns_manager;
    $this->typedDataManager = $typed_data_manager;
    $this->sourceFieldDefinition = $this->typedDataManager->createDataDefinition('ui_patterns_source_field');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.ui_patterns'),
      $container->get('typed_data_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceField($name, $label) {
    /** @var \Drupal\ui_patterns\Plugin\DataType\SourceField $field */
    $field = $this->typedDataManager->create($this->sourceFieldDefinition);
    $field = $field->setFieldName($name)
      ->setFieldLabel($label)
      ->setPluginId($this->pluginDefinition['id'])
      ->setPluginLabel($this->pluginDefinition['label']);
    return $field;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = NestedArray::mergeDeep(
      $this->baseConfigurationDefaults(),
      $this->defaultConfiguration(),
      $configuration
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getContextProperty($name) {
    $configuration = $this->getConfiguration();
    if (isset($configuration['context'][$name])) {
      return $configuration['context'][$name];
    }
    else {
      return NULL;
    }
  }

  /**
   * Returns generic default configuration for source mapper plugins.
   *
   * @return array
   *   An associative array with the default configuration.
   */
  protected function baseConfigurationDefaults() {
    return [
      'id' => $this->getPluginId(),
      'label' => '',
      'provider' => $this->pluginDefinition['provider'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function setConfigurationValue($key, $value) {
    $this->configuration[$key] = $value;
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    return [];
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
    // Process the block's submission handling if no errors occurred only.
    if (!$form_state->getErrors()) {

    }
  }

}
