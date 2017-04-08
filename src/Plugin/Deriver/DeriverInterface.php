<?php

namespace Drupal\ui_patterns\Plugin\Deriver;

/**
 * Interface DeriverInterface.
 *
 * @package Drupal\ui_patterns\Plugin\Deriver
 */
interface DeriverInterface {

  /**
   * Get pattern definition objects.
   *
   * @return \Drupal\ui_patterns\Plugin\DataType\Pattern[]
   *    List of pattern definitions contained in the file.
   */
  public function getPatterns();

}
