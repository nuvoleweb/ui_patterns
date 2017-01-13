<?php

namespace Drupal\ui_patterns\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Defines an interface for UI Patterns Source plugins.
 */
interface UiPatternsSourceInterface extends PluginInspectionInterface, PluginFormInterface, ConfigurablePluginInterface {

}
