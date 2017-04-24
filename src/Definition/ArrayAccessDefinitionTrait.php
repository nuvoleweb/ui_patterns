<?php

namespace Drupal\ui_patterns\Definition;

/**
 * Class ArrayAccessDefinitionTrait.
 *
 * @property $definition
 *
 * @package Drupal\ui_patterns\Definition
 */
trait ArrayAccessDefinitionTrait {

  /**
   * {@inheritdoc}
   */
  public function offsetExists($offset) {
    return array_key_exists($offset, $this->definition);
  }

  /**
   * {@inheritdoc}
   */
  public function offsetGet($offset) {
    return isset($this->definition[$offset]) ? $this->definition[$offset] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function offsetSet($offset, $value) {
    $this->definition[$offset] = $value;
  }

  /**
   * {@inheritdoc}
   */
  public function offsetUnset($offset) {
    unset($this->definition[$offset]);
  }

}
