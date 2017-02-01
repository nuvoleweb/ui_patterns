<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\PluginManagerInterface;

/**
 * Defines an interface for ui_patterns managers.
 */
interface UiPatternsManagerInterface extends PluginManagerInterface {

  /**
   * Return list of available patterns to be used as select options.
   *
   * @return array
   *    List of available patterns.
   */
  public function getPatternsOptions();

  /**
   * Return list of given pattern fields to be used as select options.
   *
   * @param string $id
   *    Patterns ID.
   *
   * @return array
   *    List of pattern fields.
   */
  public function getPatternFieldsOptions($id);

  /**
   * Build and return pattern theme definitions.
   *
   * @return array
   *    Theme definitions.
   *
   * @see ui_patterns_theme()
   */
  public function hookTheme();

  /**
   * Get patterns library info.
   *
   * @return array
   *   Array of libraries as expected by hook_library_info_build().
   *
   * @see hook_library_info_build()
   * @see ui_patterns_library_info_build()
   */
  public function hookLibraryInfoBuild();

  /**
   * Check whereas the given theme hook is an actual pattern hook.
   *
   * @param string $hook
   *    Theme hook.
   *
   * @return bool
   *    Whereas the given theme hook is an actual pattern hook.
   */
  public function isPatternHook($hook);

}
