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

  /**
   * Render patter example.
   *
   * @param string $pattern_id
   *    Pattern ID, a.k.a. the UI Patter plugin ID.
   *
   * @return array|\Drupal\Component\Render\MarkupInterface|string
   *    Render array.
   */
  public function renderExample($pattern_id);

}
