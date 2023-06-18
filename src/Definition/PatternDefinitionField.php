<?php

namespace Drupal\ui_patterns\Definition;

/**
 * Definition class for a pattern field.
 *
 * @package Drupal\ui_patterns\Definition
 */
class PatternDefinitionField implements \ArrayAccess {

  use ArrayAccessDefinitionTrait;

  /**
   * Default field values.
   *
   * @var array
   */
  protected $definition = [
    'name' => NULL,
    'label' => NULL,
    'description' => NULL,
    'type' => NULL,
    'preview' => NULL,
    'escape' => TRUE,
    'additional' => [],
  ];

  /**
   * PatternDefinitionField constructor.
   */
  public function __construct($name, $value) {
    if (is_scalar($value)) {
      $this->definition['name'] = is_numeric($name) ? $value : $name;
      $this->definition['label'] = $value;
    }
    else {
      $this->definition['name'] = !isset($value['name']) ? $name : $value['name'];
      $this->definition['label'] = $value['label'];
      $this->definition = $value + $this->definition;
    }
  }

  /**
   * Return array definition.
   *
   * @return array
   *   Array definition.
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

  /**
   * Get Preview property.
   *
   * @return mixed
   *   Property value.
   */
  public function getPreview() {
    return $this->definition['preview'];
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
    $this->definition['preview'] = $preview;
    return $this;
  }

  /**
   * Get Escape property.
   *
   * @return bool
   *   Property value.
   */
  public function getEscape() {
    return $this->definition['escape'];
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
    $this->definition['escape'] = $escape;
    return $this;
  }

  /**
   * Get Additional property.
   *
   * @return array
   *   Property value.
   */
  public function getAdditional() {
    return $this->definition['additional'];
  }

  /**
   * Set Additional property.
   *
   * @param array $additional
   *   Property value.
   *
   * @return $this
   */
  public function setAdditional(array $additional) {
    $this->definition['additional'] = $additional;
    return $this;
  }

}
