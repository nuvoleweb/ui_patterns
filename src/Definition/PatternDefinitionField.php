<?php

namespace Drupal\ui_patterns\Definition;

/**
 * Class PatternDefinitionField.
 *
 * @package Drupal\ui_patterns\Definition
 */
class PatternDefinitionField implements \ArrayAccess {

  /**
   * Default field values.
   *
   * @var array
   */
  protected $values = [
    'name' => NULL,
    'label' => NULL,
    'description' => NULL,
    'type' => NULL,
    'preview' => NULL,
    'escape' => TRUE,
  ];

  /**
   * PatternDefinitionField constructor.
   */
  public function __construct($name, $value) {
    if (is_scalar($value)) {
      $this->values['name'] = is_numeric($name) ? $value : $name;
      $this->values['label'] = $value;
    }
    else {
      $this->values['name'] = !isset($value['name']) ? $name : $value['name'];
      $this->values['label'] = $value['label'];
      $this->values = $value + $this->values;
    }
  }

  /**
   * Get Name property.
   *
   * @return mixed
   *   Property value.
   */
  public function getName() {
    return $this->values['name'];
  }

  /**
   * Get Label property.
   *
   * @return mixed
   *   Property value.
   */
  public function getLabel() {
    return $this->values['label'];
  }

  /**
   * Get Description property.
   *
   * @return string
   *   Property value.
   */
  public function getDescription() {
    return $this->values['description'];
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
    $this->values['description'] = $description;
    return $this;
  }

  /**
   * Get Type property.
   *
   * @return string
   *   Property value.
   */
  public function getType() {
    return $this->values['type'];
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
    $this->values['type'] = $type;
    return $this;
  }

  /**
   * Get Preview property.
   *
   * @return mixed
   *   Property value.
   */
  public function getPreview() {
    return $this->values['preview'];
  }

  /**
   * Set Preview property.
   *
   * @param mixed $preview
   *   Property value.
   *
   * @return $this
   */
  public function setPreview($preview) {
    $this->values['preview'] = $preview;
    return $this;
  }

  /**
   * Get Escape property.
   *
   * @return bool
   *   Property value.
   */
  public function getEscape() {
    return $this->values['escape'];
  }

  /**
   * Set Escape property.
   *
   * @param bool $escape
   *   Property value.
   *
   * @return $this
   */
  public function setEscape($escape) {
    $this->values['escape'] = $escape;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function offsetExists($offset) {
    return array_key_exists($offset, $this->values);
  }

  /**
   * {@inheritdoc}
   */
  public function offsetGet($offset) {
    return isset($this->values[$offset]) ? $this->values[$offset] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function offsetSet($offset, $value) {
    $this->values[$offset] = $value;
  }

  /**
   * {@inheritdoc}
   */
  public function offsetUnset($offset) {
  }

  /**
   * Return array definition.
   *
   * @return array
   *    Array definition.
   */
  public function toArray() {
    return $this->values;
  }

}
