<?php declare(strict_types = 1);

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Interface for source_provider plugins.
 */
interface SourceInterface extends PluginFormInterface, ConfigurableInterface {

  /**
   * Returns the translated plugin label.
   */
  public function label(): string;

  public function getData(): mixed;

  public function getPropId(): string;
  public function getPropDefinition(): mixed;
}
