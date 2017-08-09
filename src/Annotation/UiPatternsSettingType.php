<?php

namespace Drupal\ui_patterns\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a UI Patterns Setting Type annotation object.
 *
 * @see \Drupal\ui_patterns\UiPatternsSettingManager
 * @see plugin_api
 *
 * @Annotation
 */
class UiPatternsSettingType extends Plugin {

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

}
