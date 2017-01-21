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
    // Create a list of all directories to scan. This includes all module
    // directories and directories of the default theme and all of its possible
    // base themes.
    $directories = $this->getDefaultAndBaseThemesDirectories($themeHandler) + $moduleHandler->getModuleDirectories();

    $this->setYamlDiscovery(new YamlDiscovery('ui_patterns', $directories));
  }

  /**
   * Sets the YamlDiscovery.
   *
   * @param \Drupal\ui_patterns\Plugin\Discovery\YamlDiscovery $discovery
   *   YamlDiscovery.
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
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $themeHandler
   *   ThemeHandlerInterface.
   *
   * @return array
   *   An array containing directory paths.
   */
  protected function getDefaultAndBaseThemesDirectories(ThemeHandlerInterface $themeHandler) {
    $defaultTheme = $themeHandler->getDefault();
    $baseThemes = $themeHandler->getBaseThemes($themeHandler->listInfo(), $defaultTheme);
    $themeDirectories = $themeHandler->getThemeDirectories();

    $directories = array();
    $directories[$defaultTheme] = $themeDirectories[$defaultTheme];
    foreach ($baseThemes as $name => $theme) {
      $directories[$name] = $themeDirectories[$name];
    }

    return $directories;
  }

}
