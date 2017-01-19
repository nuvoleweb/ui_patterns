<?php

namespace Drupal\ui_patterns\Plugin\Discovery;

use Drupal\Component\FileCache\FileCacheFactory;
use Drupal\Core\Discovery\YamlDiscovery as CoreYamlDiscovery;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Site\Settings;

/**
 * Provides recursive discovery for YAML files in all module directories and
 * directories of the default theme and all of its possible base themes.
 */
class YamlDiscovery extends CoreYamlDiscovery {

  /**
   * Constructs a YamlDiscovery object.
   *
   * @param $name
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $themeHandler
   */
  public function __construct($name, ModuleHandlerInterface $moduleHandler, ThemeHandlerInterface $themeHandler) {
    // Create a list of all directories to scan. This includes all module
    // directories and directories of the default theme and all of its possible
    // base themes.
    $directories = $this->getDefaultAndBaseThemesDirectories($themeHandler) + $moduleHandler->getModuleDirectories();

    parent::__construct($name, $directories);
  }

  /**
   * {@inheritdoc}
   */
  public function findAll() {
    $files = $this->findFiles();
    $processFiles = array_keys($files);

    $file_cache = FileCacheFactory::get('yaml_discovery:' . $this->name);

    // Try to load from the file cache first.
    foreach ($processFiles as $i => $file) {
      $data = $file_cache->get($file);
      if ($data) {
        $files[$file]['data'] = $data;
        unset($processFiles[$i]);
      }
    }

    // If there are files left that were not returned from the cache, load and
    // parse them now.
    if ($processFiles) {
      foreach ($processFiles as $file) {
        // If a file is empty or its contents are commented out, return an empty
        // array instead of NULL for type consistency.
        $files[$file]['data'] = $this->decode($file);
        $file_cache->set($file, $files[$file]['data']);
      }
    }

    // Create array keyed by provider with merged data as values.
    $all = array();
    foreach ($files as $file => $value) {
      $provider = $value['provider'];
      if (isset($all[$provider])) {
        $all[$provider] += $value['data'];
      }
      else {
        $all[$provider] = $value['data'];
      }
    }
    return $all;
  }
  
  /**
   * Returns an array with file paths as keys.
   *
   * @return array
   */
  protected function findFiles() {
    // Add options for file scan.
    $options = array('nomask' => $this->getNomask());

    // Recursively scan the directories for definition files.
    $files = array();
    foreach ($this->directories as $provider => $directory) {
      $found = file_scan_directory($directory, '/\.' . $this->name . '\.yml$/', $options);
      foreach ($found as $file) {
        $files[$file->uri] = array('provider' => $provider);
      }

    }
    return $files;
  }

  /**
   * Returns an array containing the directory paths of the default theme and
   * all its possible base themes.
   *
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $themeHandler
   * @return array
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

  /**
   * Returns a regular expression for directories to be excluded in a file scan.
   *
   * @return string
   */
  protected function getNomask() {
    $ignoreDirs = Settings::get('file_scan_ignore_directories', []);
    // We add 'tests' directory to the ones found in settings.
    $ignoreDirs[] = 'tests';
    array_walk($ignoreDirs, function(&$value) {
      $value = preg_quote($value, '/');
    });
    return '/^' . implode('|', $ignoreDirs) . '$/';
  }
}
