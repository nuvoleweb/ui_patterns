<?php

namespace Drupal\ui_patterns\Definition;

/**
 * Class PatternDefinitionField.
 *
 * @package Drupal\ui_patterns\Definition
 */
class PatternDefinitionField implements \ArrayAccess {

  private $name;
  private $label;
  private $description = NULL;
  private $type = NULL;
  private $preview = NULL;
  private $escape = TRUE;

  /**
   * PatternDefinitionField constructor.
   */
  public function __construct($name, $label) {
    $this->name = $name;
    $this->label = $label;
  }

  /**
   * Get Name property.
   *
   * @return mixed
   *   Property value.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Get Label property.
   *
   * @return mixed
   *   Property value.
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * Get Description property.
   *
   * @return string
   *   Property value.
   */
  public function getDescription() {
    return $this->description;
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
    $this->description = $description;
    return $this;
  }

  /**
   * Get Type property.
   *
   * @return string
   *   Property value.
   */
  public function getType() {
    return $this->type;
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
    $this->type = $type;
    return $this;
  }

  /**
   * Get Preview property.
   *
   * @return mixed
   *   Property value.
   */
  public function getPreview() {
    return $this->preview;
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
    $this->preview = $preview;
    return $this;
  }

  /**
   * Get Escape property.
   *
   * @return bool
   *   Property value.
   */
  public function getEscape() {
    return $this->escape;
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
    $this->escape = $escape;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function offsetExists($offset) {
    return property_exists($this, $offset);
  }

  /**
   * {@inheritdoc}
   */
  public function offsetGet($offset) {
    return isset($this->{$offset}) ? $this->{$offset} : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function offsetSet($offset, $value) {
    $this->{$offset} = $value;
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
    return [
      'name' => $this->getName(),
      'label' => $this->getLabel(),
      'description' => $this->getDescription(),
      'type' => $this->getType(),
      'preview' => $this->getPreview(),
      'escape' => $this->getEscape(),
    ];
  }

}
