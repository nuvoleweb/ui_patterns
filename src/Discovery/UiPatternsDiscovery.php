<?php

namespace Drupal\ui_patterns\Discovery;

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
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   Module handler service.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   Theme handler service.
   */
  public function __construct(ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler) {
    parent::__construct('ui_patterns', []);

    // Use our discovery instead of the one set in the parent class.
    // Create a list of all directories to scan. This includes all module
    // directories and directories of the default theme and all of its possible
    // base themes.
    $directories = $this->getDefaultAndBaseThemesDirectories($theme_handler) + $module_handler->getModuleDirectories();

    $this->setYamlDiscovery(new YamlDiscovery('ui_patterns', $directories));
  }

  /**
   * Sets the YamlDiscovery.
   *
   * @param \Drupal\ui_patterns\Discovery\YamlDiscovery $discovery
   *   YamlDiscovery instance.
   */
  public function setYamlDiscovery(YamlDiscovery $discovery) {
    $this->discovery = $discovery;
  }

  /**
   * Returns an array containing theme directory paths.
   *
   * Returns the directory paths of the default theme and all its possible base
   * themes.
   *
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   Theme handler service.
   *
   * @return array
   *   An array containing directory paths.
   */
  protected function getDefaultAndBaseThemesDirectories(ThemeHandlerInterface $theme_handler) {
    $default_theme = $theme_handler->getDefault();
    $base_themes = $theme_handler->getBaseThemes($theme_handler->listInfo(), $default_theme);
    $theme_directories = $theme_handler->getThemeDirectories();

    $directories = [];
    $directories[$default_theme] = $theme_directories[$default_theme];
    foreach ($base_themes as $name => $theme) {
      $directories[$name] = $theme_directories[$name];
    }

    return $directories;
  }

}
