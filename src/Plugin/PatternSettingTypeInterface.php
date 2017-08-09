<?php

namespace Drupal\ui_patterns\Plugin;

use Drupal\Component\Plugin\ConfigurablePluginInterface;

/**
 * Defines an interface for UI Patterns setting type plugins.
 */
interface PatternSettingTypeInterface extends ConfigurablePluginInterface {


  /**
   * Builds the configuration form.
   *
   * @param [] $form
   *   The base form
   * @param string $value
   *   The stored default value
   * @return []
   *    The configuration form.
   */
  public function buildConfigurationForm(array $form, $value);


  /**
   * Preprocess setting variable .
   * @param [] $variables
   *   Card template variables
   * @param string $value
   *   The stored value
   */
  public function preprocess($value, $context);
}
