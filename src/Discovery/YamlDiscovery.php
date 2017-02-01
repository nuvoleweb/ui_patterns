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
    $file_cache = FileCacheFactory::get('yaml_discovery:' . $this->name);

    // Try to load from the file cache first.
    foreach ($files as $file_name => $file) {
      $data = $file_cache->get($file_name);
      if ($data) {
        $files[$file_name]['data'] = $data;
      }
      else {
        // If a file is empty or its contents are commented out, return an empty
        // array instead of NULL for type consistency.
        $files[$file_name]['data'] = array_map(function ($value) use ($file) {
          $value['base path'] = $file['base path'];
          return $value;
        }, $this->decode($file_name));
        $file_cache->set($file_name, $files[$file_name]['data']);
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
    // Recursively scan the directories for definition files.
    $files = [];
    foreach ($this->directories as $provider => $directory) {
      foreach ($this->fileScanDirectory($directory) as $file) {
        $files[$file->uri] = [
          'provider' => $provider,
          'base path' => dirname($file->uri),
        ];
      }
    }
    return $files;
  }

  /**
   * Wrapper method for global function call.
   *
   * @see file.inc
   */
  public function fileScanDirectory($directory) {
    $options = ['nomask' => $this->getNomask()];
    $mask = '/\.' . $this->name . '\.yml$/';
    return file_scan_directory($directory, $mask, $options, 0);
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
