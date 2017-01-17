<?php

namespace Drupal\ui_patterns\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Defines an interface for UI Patterns Source plugins.
 */
interface UiPatternsSourceInterface extends PluginInspectionInterface, PluginFormInterface, ConfigurablePluginInterface, ContainerFactoryPluginInterface {

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

}
