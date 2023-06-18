<?php

namespace Drupal\ui_patterns\Plugin;

/**
 * Interface for UI Pattern plugins.
 *
 * @package Drupal\ui_patterns
 */
interface PatternInterface {

  /**
   * Get theme implementation for current pattern.
   *
   * @see ui_patterns_theme()
   *
   * @return array
   *   Theme implementation array.
   */
  public function getThemeImplementation();

  /**
   * Get library definitions for current pattern.
   *
   * @see ui_patterns_library_info_build()
   *
   * @return array
   *   Library definitions array.
   */
  public function getLibraryDefinitions();

}
