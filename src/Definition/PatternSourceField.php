<?php

namespace Drupal\ui_patterns\Definition;

/**
 * Class PatternSourceField.
 *
 * @package Drupal\ui_patterns\Definition
 */
class PatternSourceField {

  const FIELD_KEY_SEPARATOR = ':';

  private $fieldName;
  private $fieldLabel;
  private $pluginId;
  private $pluginLabel;

  /**
   * SourceField constructor.
   */
  public function __construct($field_name, $field_label, $plugin_id, $plugin_label) {
    $this->fieldName = $field_name;
    $this->fieldLabel = $field_label;
    $this->pluginId = $plugin_id;
    $this->pluginLabel = $plugin_label;
  }

  /**
   * Get FieldName property.
   *
   * @return mixed
   *   Property value.
   */
  public function getFieldName() {
    return $this->fieldName;
  }

  /**
   * Set FieldName property.
   *
   * @param mixed $fieldName
   *   Property value.
   *
   * @return $this
   */
  public function setFieldName($fieldName) {
    $this->fieldName = $fieldName;
    return $this;
  }

  /**
   * Get FieldLabel property.
   *
   * @return mixed
   *   Property value.
   */
  public function getFieldLabel() {
    return $this->fieldLabel;
  }

  /**
   * Set FieldLabel property.
   *
   * @param mixed $fieldLabel
   *   Property value.
   *
   * @return $this
   */
  public function setFieldLabel($fieldLabel) {
    $this->fieldLabel = $fieldLabel;
    return $this;
  }

  /**
   * Get Plugin property.
   *
   * @return mixed
   *   Property value.
   */
  public function getPluginId() {
    return $this->pluginId;
  }

  /**
   * Set Plugin property.
   *
   * @param mixed $pluginId
   *   Property value.
   *
   * @return $this
   */
  public function setPluginId($pluginId) {
    $this->pluginId = $pluginId;
    return $this;
  }

  /**
   * Get PluginLabel property.
   *
   * @return mixed
   *   Property value.
   */
  public function getPluginLabel() {
    return $this->pluginLabel;
  }

  /**
   * Set PluginLabel property.
   *
   * @param mixed $pluginLabel
   *   Property value.
   *
   * @return $this
   */
  public function setPluginLabel($pluginLabel) {
    $this->pluginLabel = $pluginLabel;
    return $this;
  }

  /**
   * Get unique field key.
   *
   * @return string
   *   Field key.
   */
  public function getFieldKey() {
    return $this->getPluginId() . self::FIELD_KEY_SEPARATOR . $this->getFieldName();
  }

}
