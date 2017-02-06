<?php

namespace Drupal\ui_patterns;

/**
 * Interface UiPatternInterface.
 *
 * @package Drupal\ui_patterns
 */
interface UiPatternInterface {

  /**
   * Get the pattern definition.
   */
  public function definition();

  /**
   * Get the pattern javascript.
   */
  public function javascript();

  /**
   * Get the pattern stylesheet.
   */
  public function stylesheet();

  /**
   * Get the pattern template.
   */
  public function template();

  /**
   * Check if the pattern is a pattern hook.
   *
   * @param string $hook
   *   The hook.
   *
   * @return bool
   *   True or False.
   */
  public function isPatternHook($hook);

  /**
   * Get the array that goes into hook_theme().
   *
   * @return array
   *   The hook_theme entry element.
   */
  public function hookTheme();

}
