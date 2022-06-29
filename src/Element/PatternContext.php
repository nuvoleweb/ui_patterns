<?php

namespace Drupal\ui_patterns\Element;

/**
 * Represent the context in which the pattern is being rendered.
 *
 * @package Drupal\ui_patterns\Context
 */
class PatternContext {

  /**
   * Pattern context type.
   *
   * @var string
   */
  protected $type = '';

  /**
   * Context properties.
   *
   * @var array
   */
  protected $properties = [];

  /**
   * PatternContext constructor.
   *
   * @param string $type
   *   Pattern context type.
   * @param array $values
   *   Initial context values.
   */
  public function __construct($type, array $values = []) {
    $this->type = $type;
    unset($values['type']);
    foreach ($values as $name => $value) {
      $this->setProperty($name, $value);
    }
  }

  /**
   * Get pattern context property.
   *
   * @return mixed
   *   Property value.
   */
  public function getProperty($name) {
    return $this->properties[$name] ?? NULL;
  }

  /**
   * Set pattern context property.
   *
   * @param string $name
   *   Property name.
   * @param mixed $value
   *   Property value.
   */
  public function setProperty($name, $value) {
    $this->properties[$name] = $value;
  }

  /**
   * Check whereas the current context is of a given type.
   *
   * @param string $type
   *   Type string.
   *
   * @return bool
   *   Whereas the current context is of a given type.
   */
  public function isOfType($type) {
    return $this->type == $type;
  }

  /**
   * Get context type.
   *
   * @return string
   *   Context type.
   */
  public function getType() {
    return $this->type;
  }

}
