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
  public function offsetExists($offset): bool {
    return array_key_exists($offset, $this->definition);
  }

  /**
   * {@inheritdoc}
   */
  public function offsetGet($offset): mixed {
    return $this->definition[$offset] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function offsetSet($offset, $value): void {
    $this->definition[$offset] = $value;
  }

  /**
   * {@inheritdoc}
   */
  public function offsetUnset($offset): void {
    unset($this->definition[$offset]);
  }

}
