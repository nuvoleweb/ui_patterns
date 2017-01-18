<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\PluginManagerInterface;

/**
 * Defines an interface for ui_patterns managers.
 */
interface UiPatternsManagerInterface extends PluginManagerInterface {

  /**
   * Render patter preview.
   *
   * @param string $pattern_id
   *    Pattern ID, a.k.a. the UI Patter plugin ID.
   *
   * @return array|\Drupal\Component\Render\MarkupInterface|string
   *    Render array.
   */
  public function renderPreview($pattern_id);

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

}
