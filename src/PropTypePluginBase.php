<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for prop_type plugins.
 */
abstract class PropTypePluginBase extends PluginBase implements PropTypeInterface {

  /**
   * {@inheritdoc}
   */
  public function label() {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['label'];
  }

  public function getSchema():array {
    return (array) $this->pluginDefinition['schema'];
  }

}
