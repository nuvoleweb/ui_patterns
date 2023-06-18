<?php

namespace Drupal\ui_patterns\Plugin\Deriver;

/**
 * Deriver interface for YAML-based pattern definitions.
 *
 * @package Drupal\ui_patterns\Plugin\Deriver
 */
interface YamlPatternsDeriverInterface extends PatternsDeriverInterface {

  /**
   * Get list of possible yaml definition file extensions.
   *
   * @return string[]
   *   List of allowed file extensions.
   */
  public function getFileExtensions();

  /**
   * Wrapper method for global function call.
   *
   * @see file.inc
   */
  public function fileScanDirectory($directory);

}
