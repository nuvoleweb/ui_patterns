<?php

namespace Drupal\ui_patterns\Plugin\Deriver;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Component\Serialization\Yaml;
use Drupal\Core\Extension\ExtensionDiscovery;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Site\Settings;

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
   * The app root.
   *
   * @var string
   */
  protected $root;

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
   * Extension discovery class.
   *
   * @var \Drupal\Core\Extension\ExtensionDiscovery
   */
  protected $extensionDiscovery;

  /**
   * List of extension locations.
   *
   * @var array
   */
  protected $extensions = [];

  /**
   * Constructs an EntityDeriver object.
   *
   * @param string $base_plugin_id
   *   The base plugin ID.
   * @param string $root
   *    Application root directory.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   Module handler service.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   Theme handler service.
   */
  public function __construct($base_plugin_id, $root, ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler) {
    $this->basePluginId = $base_plugin_id;
    $this->root = $root;
    $this->moduleHandler = $module_handler;
    $this->themeHandler = $theme_handler;
    $this->extensionDiscovery = new ExtensionDiscovery($root);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $base_plugin_id,
      $container->get('app.root'),
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
    if (isset($theme_directories[$default_theme])) {
      $directories[$default_theme] = $theme_directories[$default_theme];
      foreach ($base_themes as $name => $theme) {
        $directories[$name] = $theme_directories[$name];
      }
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
    $files = [];
    foreach ($this->getDirectories() as $provider => $directory) {
      foreach ($this->fileScanDirectory($directory) as $pathname => $file) {
        $host_extension = $this->getHostExtension($pathname);
        if ($host_extension == FALSE || $host_extension == $provider) {
          $content = file_get_contents($pathname);
          $files[$pathname] = [
            'provider' => $provider,
            'base path' => dirname($pathname),
            'definitions' => Yaml::decode($content),
          ];
        }
      }
    }

    return $files;
  }

  /**
   * Get extension name that hosts the given YAML definition file.
   *
   * @param string $pathname
   *    YAML definition file full path.
   *
   * @return bool|string
   *    Either extension machine name or FALSE if not found.
   */
  protected function getHostExtension($pathname) {
    $extensions = $this->getExtensionLocations();
    $parts = explode(DIRECTORY_SEPARATOR, $pathname);
    while (!empty($parts)) {
      $path = implode(DIRECTORY_SEPARATOR, $parts);
      if (isset($extensions[$path])) {
        return $extensions[$path];
      }
      array_pop($parts);
    }
    return FALSE;
  }

  /**
   * Get extension locations.
   *
   * @return array
   *    Array of extensions keyed by their path location.
   */
  protected function getExtensionLocations() {
    /** @var \Drupal\Core\Extension\Extension[] $extensions */
    if (empty($this->extensions)) {
      $extensions = $this->extensionDiscovery->scan('theme') + $this->extensionDiscovery->scan('module');
      foreach ($extensions as $name => $extension) {
        $this->extensions[$this->root . DIRECTORY_SEPARATOR . $extension->getPath()] = $name;
      }
    }
    return $this->extensions;
  }

  /**
   * Wrapper method for global function call.
   *
   * @see file.inc
   */
  public function fileScanDirectory($directory) {
    $options = ['nomask' => $this->getNoMask()];
    $mask = '/\.ui_patterns\.yml$/';
    return file_scan_directory($directory, $mask, $options, 0);
  }

  /**
   * Returns a regular expression for directories to be excluded in a file scan.
   *
   * @return string
   *   Regular expression.
   */
  protected function getNoMask() {
    $ignore = Settings::get('file_scan_ignore_directories', []);
    // We add 'tests' directory to the ones found in settings.
    $ignore[] = 'tests';
    array_walk($ignore, function (&$value) {
      $value = preg_quote($value, '/');
    });
    return '/^' . implode('|', $ignore) . '$/';
  }

}
