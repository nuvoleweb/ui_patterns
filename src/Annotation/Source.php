<?php declare(strict_types = 1);

namespace Drupal\ui_patterns\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines source_provider annotation object.
 *
 * @Annotation
 */
final class Source extends Plugin {

  /**
   * The plugin ID.
   */
  public readonly string $id;

  /**
   * The human-readable name of the plugin.
   *
   * @ingroup plugin_translatable
   */
  public readonly string $title;

  /**
   * The description of the plugin.
   *
   * @ingroup plugin_translatable
   */
  public readonly string $description;

  /**
   * An array of prop types the source provider supports.
   *
   * @var array
   */
  public $prop_types = [];


}
