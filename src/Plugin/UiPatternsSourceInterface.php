<?php

namespace Drupal\ui_patterns\Plugin;

use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Defines an interface for UI Patterns Source plugins.
 */
interface UiPatternsSourceInterface extends PluginInspectionInterface, ContainerFactoryPluginInterface, ConfigurablePluginInterface {

  /**
   * Source field factory method.
   *
   * @param string $name
   *    Machine name.
   * @param string $label
   *    Human readable label.
   *
   * @return \Drupal\ui_patterns\Plugin\DataType\SourceField
   *    Source field instance.
   */
  public function getSourceField($name, $label);

  /**
   * Return list of source fields.
   *
   * @return \Drupal\ui_patterns\Plugin\DataType\SourceField[]
   *    List of source fields.
   */
  public function getSourceFields();

  /**
   * Get context property value, if any.
   *
   * @param string $name
   *    Context property name.
   *
   * @return mixed
   *    Context property value.
   */
  public function getContextProperty($name);

}
