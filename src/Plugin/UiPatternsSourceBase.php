<?php

namespace Drupal\ui_patterns\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\TypedData\TypedDataManager;
use Drupal\ui_patterns\UiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for UI Patterns Source plugins.
 */
abstract class UiPatternsSourceBase extends PluginBase implements UiPatternsSourceInterface {

  use StringTranslationTrait;

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
    $this->configuration = $configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'context' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getContextProperty($name) {
    $configuration = $this->getConfiguration();
    if (isset($configuration['context'][$name]) && !empty($configuration['context'][$name])) {
      return $configuration['context'][$name];
    }
    $reflection = new \ReflectionClass($this);
    throw new PluginException($this->t("Context property '@property' from @class is missing or empty.", ['@property' => $name, '@class' => $reflection->name]));
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    return [];
  }

}
