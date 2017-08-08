<?php

namespace Drupal\ui_patterns\Definition;

/**
 * Class PatternDefinitionSetting.
 *
 * @package Drupal\ui_patterns\Definition
 */
class PatternDefinitionSetting implements \ArrayAccess {

  use ArrayAccessDefinitionTrait;

  /**
   * Default setting values.
   *
   * @var array
   */
  protected $definition = [
    'name' => NULL,
    'label' => NULL,
    'description' => NULL,
    'type' => NULL,
    'required' => false,
    'default_value' => NULL,
    'options' => NULL,
  ];

  /**
   * PatternDefinitionSetting constructor.
   */
  public function __construct($name, $value) {
    if (is_scalar($value)) {
      $this->definition['name'] = is_numeric($name) ? $value : $name;
      $this->definition['label'] = $value;
    }
    else {
      $this->definition['name'] = !isset($value['name']) ? $name : $value['name'];
      $this->definition['label'] = $value['label'];
      $this->definition['required'] = isset($value['required']) ? $value['required'] : FALSE;
      $this->definition['default_value'] = isset($value['default_value']) ? $value['default_value'] : NULL;
      $this->definition['options'] = isset($value['options']) ? $value['options'] : NULL;
      $this->definition = $value + $this->definition;
    }
  }

  /**
   * Return array definition.
   *
   * @return array
   *    Array definition.
   */
  public function toArray() {
    return $this->definition;
  }

  /**
   * Get Name property.
   *
   * @return mixed
   *   Property value.
   */
  public function getName() {
    return $this->definition['name'];
  }

  /**
   * Get Label property.
   *
   * @return mixed
   *   Property value.
   */
  public function getLabel() {
    return $this->definition['label'];
  }

  /**
   * Get required property.
   *
   * @return mixed
   *   Property value.
   */
  public function getRequired() {
    return $this->definition['required'];
  }

  /**
   * Get options array.
   *
   * @return mixed
   *   Property option.
   */
  public function getOptions() {
    return $this->definition['options'];
  }
  /**
   * Get default value property.
   *
   * @return mixed
   *   Property value.
   */
  public function getDefaultValue() {
    return $this->definition['default_value'];
  }

  /**
   * Get Description property.
   *
   * @return string
   *   Property value.
   */
  public function getDescription() {
    return $this->definition['description'];
  }

  /**
   * Set Description property.
   *
   * @param string $description
   *   Property value.
   *
   * @return $this
   */
  public function setDescription($description) {
    $this->definition['description'] = $description;
    return $this;
  }

  /**
   * Get Type property.
   *
   * @return string
   *   Property value.
   */
  public function getType() {
    return $this->definition['type'];
  }

  /**
   * Set Type property.
   *
   * @param string $type
   *   Property value.
   *
   * @return $this
   */
  public function setType($type) {
    $this->definition['type'] = $type;
    return $this;
  }
}
