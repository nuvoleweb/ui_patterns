<?php

namespace Drupal\ui_patterns\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines prop_type annotation object.
 *
 * @Annotation
 */
class PropType extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The description of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

  /**
   * The json schema of the plugin matches.
   *
   * @var array
   */
  public $schema;

  /**
   * The priority of the PropType.
   *
   * @var int
   */
  public int $priority;

}
