<?php

namespace Drupal\ui_patterns\Plugin\Discovery;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Plugin\Discovery\YamlDiscovery as PluginYamlDiscovery;

/**
 * Allows ui_patterns.yml files to define pattern plugin definitions.
 */
class UiPatternsDiscovery extends PluginYamlDiscovery {

  /**
   * Constructs an UiPatternsDiscovery object.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   ModuleHanderInterface.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $themeHandler
   *   ThemeHandlerInterface.
   */
  public function __construct(ModuleHandlerInterface $moduleHandler, ThemeHandlerInterface $themeHandler) {
    parent::__construct('ui_patterns', array());
    // Use our discovery instead of the one set in the parent class.
    $this->discovery = new YamlDiscovery('ui_patterns', $moduleHandler, $themeHandler);
  }

}
