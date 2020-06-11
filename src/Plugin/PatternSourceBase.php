<?php

namespace Drupal\ui_patterns\Plugin;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\ui_patterns\Definition\PatternSourceField;
use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Base class for UI Patterns Source plugins.
 */
abstract class PatternSourceBase extends PluginBase implements PatternSourceInterface, PluginInspectionInterface, ConfigurableInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getSourceField($name, $label) {
    return new PatternSourceField($name, $label, $this->pluginDefinition['id'], $this->pluginDefinition['label']);
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
    $message = sprintf("Context property '%s' from %s is missing or empty.", $name, $reflection->name);
    throw new PluginException($message);
  }

}
