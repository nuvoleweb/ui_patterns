<?php declare(strict_types = 1);

namespace Drupal\ui_patterns;

/**
 * Interface for source_provider plugins.
 */
interface SourceProviderInterface {

  /**
   * Returns the translated plugin label.
   */
  public function label(): string;

}
