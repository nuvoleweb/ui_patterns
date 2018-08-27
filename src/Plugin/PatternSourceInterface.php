<?php

namespace Drupal\ui_patterns\Plugin;

/**
 * Defines an interface for UI Patterns Source plugins.
 */
interface PatternSourceInterface {

  /**
   * Source field factory method.
   *
   * @param string $name
   *   Machine name.
   * @param string $label
   *   Human readable label.
   *
   * @return \Drupal\ui_patterns\Definition\PatternSourceField
   *   Source field instance.
   */
  public function getSourceField($name, $label);

  /**
   * Return list of source fields.
   *
   * @return \Drupal\ui_patterns\Definition\PatternSourceField[]
   *   List of source fields.
   */
  public function getSourceFields();

  /**
   * Get context property value, if any.
   *
   * @param string $name
   *   Context property name.
   *
   * @return mixed
   *   Context property value.
   */
  public function getContextProperty($name);

}
