<?php

namespace Drupal\ui_patterns;

/**
 * Interface UiPatternInterface.
 *
 * @package Drupal\ui_patterns
 */
interface UiPatternInterface {

  /**
   * Get pattern fields.
   *
   * @return array
   *    Array of fields keyed by field name.
   */
  public function getFields();

  /**
   * Get list of pattern libraries, be them locally or globally declared.
   *
   * @return array
   *    List of libraries, suitable for "#attach" render array element.
   */
  public function getLibraries();

}
