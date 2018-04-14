<?php

namespace Drupal\ui_patterns\Plugin\Deriver;

/**
 * Interface PatternsDeriverInterface.
 *
 * @package Drupal\ui_patterns\Plugin\Deriver
 */
interface PatternsDeriverInterface {

  /**
   * Get pattern definition objects.
   *
   * @return \Drupal\ui_patterns\Definition\PatternDefinition[]
   *   List of pattern definitions contained in the file.
   */
  public function getPatterns();

}
