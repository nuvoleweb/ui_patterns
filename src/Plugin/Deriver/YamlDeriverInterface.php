<?php

namespace Drupal\ui_patterns\Plugin\Deriver;

/**
 * Interface YamlDeriverInterface.
 *
 * @package Drupal\ui_patterns\Plugin\Deriver
 */
interface YamlDeriverInterface {

  /**
   * Get list of possible yaml definition file extensions.
   *
   * @return string[]
   *    List of allowed file extensions.
   */
  public function getFileExtensions();

  /**
   * Wrapper method for global function call.
   *
   * @see file.inc
   */
  public function fileScanDirectory($directory);

  /**
   * Returns YAML definitions contained in given file.
   *
   * @param string $file_path
   *    Full path to definition file.
   * @param string $provider
   *    Machine name of the provider Drupal extension.
   *
   * @return array
   *    Array describing current definition file having the following elements:
   *      - provider: Drupal extension machine name.
   *      - file name: file name component of file path.
   *      - base path: directory name component of file path.
   *      - definitions: array of pattern definitions keyed by pattern ID.
   */
  public function getFileDefinitions($file_path, $provider);

}
