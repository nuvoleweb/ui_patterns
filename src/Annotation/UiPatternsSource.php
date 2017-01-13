<?php

namespace Drupal\ui_patterns\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a UI Patterns Source item annotation object.
 *
 * @see \Drupal\ui_patterns\Plugin\UiPatternsSourceManager
 * @see plugin_api
 *
 * @Annotation
 */
class UiPatternsSource extends Plugin {

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
