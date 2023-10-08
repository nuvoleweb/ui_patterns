<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Base class for source_provider plugins.
 */
abstract class SourcePluginBase extends PluginBase implements SourceInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->setConfiguration($configuration);
  }

  protected array $propDefinition;

  protected $propId;

  /**
   * {@inheritdoc}
   */
  public function label(): string {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['label'];
  }

  /**
   *
   */
  public function buildConfigurationForm(
    array $form,
    FormStateInterface $form_state
  ) {
    return [];
  }

  /**
   *
   */
  public function validateConfigurationForm(
    array &$form,
    FormStateInterface $form_state
  ) {

  }

  /**
   *
   */
  public function submitConfigurationForm(
    array &$form,
    FormStateInterface $form_state
  ) {
    $parents = $form['#parents'];
    unset($parents[0]);
    $value = $form_state->getValue($parents);
    $this->configuration['form_value'] = $value;
  }

  /**
   *
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   *
   */
  public function setConfiguration(array $configuration) {
    if (isset($configuration['prop_definition'])) {
      $this->propDefinition = $configuration['prop_definition'];
    }
    if (isset($configuration['prop_id'])) {
      $this->propId = $configuration['prop_id'];
    }
    $this->configuration = $configuration;
  }

  /**
   *
   */
  abstract public function defaultConfiguration();

  /**
   *
   */
  public function getPropId(): string {
    return $this->propId;
  }

  /**
   *
   */
  public function getPropDefinition(): array {
    return $this->propDefinition;
  }

}
