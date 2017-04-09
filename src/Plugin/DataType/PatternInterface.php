<?php

namespace Drupal\ui_patterns\Plugin\DataType;

/**
 * Interface PatternInterface.
 *
 * @package Drupal\ui_patterns\Plugin\DataType
 */
interface PatternInterface {

  /**
   * Check whereas the pattern definition is valid or not.
   *
   * @return bool
   *    Whereas the pattern definition is valid or not.
   */
  public function isValid();

  /**
   * Get validation error messages.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup[]
   *    List of validation error messages.
   */
  public function getErrorMessages();

}
