<?php

namespace Drupal\ui_patterns\Plugin\Deriver;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Component\Serialization\Yaml;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Site\Settings;
use Symfony\Component\Finder\Finder;

/**
 * Class YamlDeriver.
 *
 * Derive pattern plugin definitions stored in YAML files.
 *
 * @package Drupal\ui_patterns\Deriver
 */
class YamlDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The base plugin ID.
   *
   * @var string
   */
  protected $basePluginId;

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * The module handler to invoke the alter hook.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs an EntityDeriver object.
   *
   * @param string $base_plugin_id
   *   The base plugin ID.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   Module handler service.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   Theme handler service.
   */
  public function __construct($base_plugin_id, ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler) {
    $this->basePluginId = $base_plugin_id;
    $this->moduleHandler = $module_handler;
    $this->themeHandler = $theme_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $base_plugin_id,
      $container->get('module_handler'),
      $container->get('theme_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    foreach ($this->getDefinitionFiles() as $file) {
      foreach ($file['definitions'] as $id => $definition) {
        $this->derivatives[$id] = $definition + $base_plugin_definition;
        $this->derivatives[$id]['id'] = $id;
        $this->derivatives[$id]['provider'] = $file['provider'];
        $this->derivatives[$id]['base path'] = $file['base path'];
      }
    }
    return $this->derivatives;
  }

  /**
   * Create a list of all directories to scan.
   *
   * This includes all module directories and directories of the default theme
   * and all of its possible base themes.
   *
   * @return array
   *   An array containing directory paths keyed by their extension name.
   */
  protected function getDirectories() {
    $default_theme = $this->themeHandler->getDefault();
    $base_themes = $this->themeHandler->getBaseThemes($this->themeHandler->listInfo(), $default_theme);
    $theme_directories = $this->themeHandler->getThemeDirectories();

    $directories = [];
    $directories[$default_theme] = $theme_directories[$default_theme];
    foreach ($base_themes as $name => $theme) {
      $directories[$name] = $theme_directories[$name];
    }

    return $directories + $this->moduleHandler->getModuleDirectories();
  }

  /**
   * Get list of definition files.
   *
   * Each entry contains:
   *  - provider: extension machine name providing the definition.
   *  - base path: base path of the definition file itself.
   *  - definitions: list definitions contained in the definition file.
   *
   * @return array
   *    List of definition files.
   */
  protected function getDefinitionFiles() {
    // We add 'tests' directory to the ones found in settings.
    $ignore = Settings::get('file_scan_ignore_directories', []);
    $ignore[] = 'tests';

    $files = [];
    foreach ($this->getDirectories() as $provider => $directory) {
      $finder = new Finder();
      $finder->name('/\.ui_patterns\.yml$/')->in($directory)->exclude($ignore);

      foreach ($finder as $file) {
        $files[$file->getPathname()] = [
          'provider' => $provider,
          'base path' => dirname($file->getPathname()),
          'definitions' => Yaml::decode($file->getContents()),
        ];
      }
    }

    return $files;
  }

}
