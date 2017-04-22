<?php

namespace Drupal\ui_patterns\Plugin\Deriver;

use Drupal\Core\Site\Settings;

/**
 * Class AbstractYamlPatternsDeriver.
 *
 * Derive pattern plugin definitions stored in YAML files.
 *
 * @package Drupal\ui_patterns\Deriver
 */
abstract class AbstractYamlPatternsDeriver extends AbstractPatternsDeriver implements YamlPatternsDeriverInterface {

  /**
   * {@inheritdoc}
   */
  public function fileScanDirectory($directory) {
    $options = ['nomask' => $this->getNoMask()];
    $extensions = $this->getFileExtensions();
    $extensions = array_map('preg_quote', $extensions);
    $extensions = implode('|', $extensions);
    return file_scan_directory($directory, "/{$extensions}$/", $options, 0);
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
