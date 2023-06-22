<?php

namespace Drupal\ui_patterns\Definition;

/**
 * Helper trait implementing PHP array access.
 *
 * @property $definition
 *
 * @package Drupal\ui_patterns\Definition
 */
trait ArrayAccessDefinitionTrait {

  /**
   * {@inheritdoc}
   */
  #[\ReturnTypeWillChange]
  public function offsetExists($offset) {
    return array_key_exists($offset, $this->definition);
  }

  /**
   * {@inheritdoc}
   */
  #[\ReturnTypeWillChange]
  public function offsetGet($offset) {
    return $this->definition[$offset] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  #[\ReturnTypeWillChange]
  public function offsetSet($offset, $value) {
    $this->definition[$offset] = $value;
  }

  /**
   * {@inheritdoc}
   */
  #[\ReturnTypeWillChange]
  public function offsetUnset($offset) {
    unset($this->definition[$offset]);
  }

}
