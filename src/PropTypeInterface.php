<?php

namespace Drupal\ui_patterns;

/**
 * Interface for prop_type plugins.
 */
interface PropTypeInterface {

  /**
   * Returns the translated plugin label.
   *
   * @return string
   *   The translated title.
   */
  public function label();

  /**
   * Returns the schema.
   *
   * @return array
   *   The schema.
   */
  public function getSchema():array;

}
