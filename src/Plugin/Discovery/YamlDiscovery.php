<?php

namespace Drupal\ui_patterns\Plugin\Discovery;

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
    if (!empty($processFiles)) {
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
   *   An array with file paths as keys.
   */
  protected function findFiles() {
    // Add options for file scan.
    $options = array('nomask' => $this->getNomask());

    // Recursively scan the directories for definition files.
    $files = array();
    foreach ($this->directories as $provider => $directory) {
      $found = $this->fileScanDirectory($directory, '/\.' . $this->name . '\.yml$/', $options);
      foreach (array_keys($found) as $file) {
        $files[$file] = array('provider' => $provider);
      }
    }
    return $files;
  }

  /**
   * Wrapper method for global function call.
   *
   * @see file.inc
   */
  public function fileScanDirectory($dir, $mask, $options = array(), $depth = 0) {
    return file_scan_directory($dir, $mask, $options, $depth);
  }

  /**
   * Returns a regular expression for directories to be excluded in a file scan.
   *
   * @return string
   *   Regular expression.
   */
  protected function getNomask() {
    $ignoreDirs = Settings::get('file_scan_ignore_directories', []);
    // We add 'tests' directory to the ones found in settings.
    $ignoreDirs[] = 'tests';
    array_walk($ignoreDirs, function (&$value) {
      $value = preg_quote($value, '/');
    });
    return '/^' . implode('|', $ignoreDirs) . '$/';
  }

}
