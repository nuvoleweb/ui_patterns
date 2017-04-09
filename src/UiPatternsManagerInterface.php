<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\PluginManagerInterface;

/**
 * Defines an interface for ui_patterns managers.
 */
interface UiPatternsManagerInterface extends PluginManagerInterface {

  /**
   * Get a fully instantiated pattern object.
   *
   * @param string $id
   *    Pattern ID.
   *
   * @return \Drupal\ui_patterns\UiPatternBase
   *    Pattern object instance.
   */
  public function getPattern($id);

  /**
   * Get a fully instantiated list of pattern objects.
   *
   * @return \Drupal\ui_patterns\UiPatternInterface[]
   *    List of pattern object instances.
   */
  public function getPatterns();

  /**
   * Return list of available patterns to be used as select options.
   *
   * @return array
   *    List of available patterns.
   */
  public function getPatternsOptions();

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
