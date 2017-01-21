<?php

namespace Drupal\ui_patterns\Discovery;

use Drupal\Component\FileCache\FileCacheFactory;
use Drupal\Core\Discovery\YamlDiscovery as CoreYamlDiscovery;
use Drupal\Core\Site\Settings;

/**
 * Provides recursive discovery for YAML files.
 *
 * Discovered data is keyed by provider (module/theme). If multiple YAML files
 * are discovered from a provider their data will be merged.
 */
class YamlDiscovery extends CoreYamlDiscovery {

  /**
   * {@inheritdoc}
   */
  public function findAll() {
    $files = $this->findFiles();
    $files = $this->loadFromCache($files);

    // Create array keyed by provider with merged data as values.
    $all = [];
    foreach ($files as $file) {
      $provider = $file['provider'];
      if (isset($all[$provider])) {
        $all[$provider] += $file['data'];
      }
      else {
        $all[$provider] = $file['data'];
      }
    }
    return $all;
  }

  /**
   * Load files from cache.
   *
   * @param array $files
   *    Files array.
   *
   * @return array
   *    Files loaded from cache.
   */
  protected function loadFromCache(array $files) {
    $process_files = array_keys($files);
    $file_cache = FileCacheFactory::get('yaml_discovery:' . $this->name);

    // Try to load from the file cache first.
    foreach ($process_files as $file) {
      $data = $file_cache->get($file);
      if ($data) {
        $files[$file]['data'] = $data;
      }
      else {
        // If a file is empty or its contents are commented out, return an empty
        // array instead of NULL for type consistency.
        $files[$file]['data'] = $this->decode($file);
        $file_cache->set($file, $files[$file]['data']);
      }
    }

    return $files;
  }

  /**
   * Returns an array with file paths as keys.
   *
   * @return array
   *   An array with file paths as keys.
   */
  protected function findFiles() {
    // Add options for file scan.
    $options = ['nomask' => $this->getNomask()];

    // Recursively scan the directories for definition files.
    $files = [];
    foreach ($this->directories as $provider => $directory) {
      $found = $this->fileScanDirectory($directory, '/\.' . $this->name . '\.yml$/', $options);
      foreach (array_keys($found) as $file) {
        $files[$file] = ['provider' => $provider];
      }
    }
    return $files;
  }

  /**
   * Wrapper method for global function call.
   *
   * @see file.inc
   */
  public function fileScanDirectory($dir, $mask, $options = [], $depth = 0) {
    return file_scan_directory($dir, $mask, $options, $depth);
  }

  /**
   * Returns a regular expression for directories to be excluded in a file scan.
   *
   * @return string
   *   Regular expression.
   */
  protected function getNomask() {
    $ignore = Settings::get('file_scan_ignore_directories', []);
    // We add 'tests' directory to the ones found in settings.
    $ignore[] = 'tests';
    array_walk($ignore, function (&$value) {
      $value = preg_quote($value, '/');
    });
    return '/^' . implode('|', $ignore) . '$/';
  }

}
