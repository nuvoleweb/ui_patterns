<?php declare(strict_types = 1);

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for source_provider plugins.
 */
abstract class SourceProviderPluginBase extends PluginBase implements SourceProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function label(): string {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['label'];
  }

}
