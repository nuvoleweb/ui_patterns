<?php

namespace Drupal\ui_patterns\Plugin\DataType;

use Drupal\Core\TypedData\Plugin\DataType\Map;

/**
 * The "ui_patterns_source_field" data type.
 *
 * @ingroup typed_data
 *
 * @DataType(
 *   id = "ui_patterns_source_field",
 *   label = @Translation("UI Patterns: Source field"),
 *   definition_class = "\Drupal\ui_patterns\Plugin\DataType\SourceFieldDefinition"
 * )
 */
class SourceField extends Map {

  /**
   * Property getter.
   *
   * @return string
   *    Property value.
   */
  public function getFieldName() {
    return $this->get('field_name')->getString();
  }

  /**
   * Property setter.
   *
   * @param string $field_name
   *    Property value.
   *
   * @return $this
   */
  public function setFieldName($field_name) {
    $this->set('field_name', $field_name);
    return $this;
  }

  /**
   * Property getter.
   *
   * @return string
   *    Property value.
   */
  public function getFieldLabel() {
    return $this->get('field_label')->getString();
  }

  /**
   * Property setter.
   *
   * @param string $field_label
   *    Property value.
   *
   * @return $this
   */
  public function setFieldLabel($field_label) {
    $this->set('field_label', $field_label);
    return $this;
  }

  /**
   * Property getter.
   *
   * @return string
   *    Property value.
   */
  public function getPluginId() {
    return $this->get('plugin')->getString();
  }

  /**
   * Property setter.
   *
   * @param string $plugin
   *    Property value.
   *
   * @return $this
   */
  public function setPluginId($plugin) {
    $this->set('plugin', $plugin);
    return $this;
  }

  /**
   * Property getter.
   *
   * @return string
   *    Property value.
   */
  public function getPluginLabel() {
    return $this->get('plugin_label')->getString();
  }

  /**
   * Property setter.
   *
   * @param string $plugin_label
   *    Property value.
   *
   * @return $this
   */
  public function setPluginLabel($plugin_label) {
    $this->set('plugin_label', $plugin_label);
    return $this;
  }

}
