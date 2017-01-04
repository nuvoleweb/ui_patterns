<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\PluginManagerInterface;

/**
 * Defines an interface for ui_patterns managers.
 */
interface UiPatternsManagerInterface extends PluginManagerInterface {

  /**
   * Get definition by associated theme hook function.
   *
   * @param string $hook
   *    Theme hook function.
   *
   * @return array
   *    Plugin definition.
   */
  public function getDefinitionByThemeHook($hook);

}
