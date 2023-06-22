<?php

namespace Drupal\ui_patterns_props_widget\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a UI Patterns Prop Widget annotation object.
 *
 * @see \Drupal\ui_patterns_props_widget\UiPatternsSettingManager
 * @see plugin_api
 *
 * @Annotation
 */
class PropWidget extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * Applies for .
   *
   * @var array
   */
  public $applies = [];

}
