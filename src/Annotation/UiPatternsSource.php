<?php

namespace Drupal\ui_patterns\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a UI Patterns Source item annotation object.
 *
 * @see \Drupal\ui_patterns\UiPatternsSourceManager
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

  /**
   * Module that must be enabled in order for the plugin to be discoverable.
   *
   * @var string
   */
  public $provider;

  /**
   * An array of tags specifying in which context the source plugin can be used.
   *
   * We should tackle this by using contexts but, until configuration entities
   * will not be exposed as typed data, we will use tags instead.
   *
   * @var array
   *
   * @link https://www.drupal.org/node/1818574
   */
  public $tags = [];

}
