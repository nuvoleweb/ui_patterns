<?php

namespace Drupal\ui_patterns_legacy\Plugin\Discovery;

use Drupal\Component\Plugin\Discovery\DiscoveryInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Plugin\Discovery\YamlDiscovery;
use Drupal\Core\Plugin\Discovery\YamlDiscoveryDecorator;

/**
 * Discover directories that contain a specific metadata file.
 *
 * @internal
 */
final class UiPatternsLegacyPluginDiscovery extends YamlDiscovery {

  /**
   * Constructs a YamlDirectoryDiscovery object.
   *
   * @param \Drupal\Component\Plugin\Discovery\DiscoveryInterface $decorated
   *   The decorated origin SDC Discovery Service.
   * @param array $directories
   *   An array of directories to scan, keyed by the provider. The value can
   *   either be a string or an array of strings. The string values should be
   *   the path of a directory to scan.
   * @param string $file_cache_key_suffix
   *   The file cache key suffix. This should be unique for each type of
   *   discovery.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system service.
   */
  public function __construct(array $directories, $file_cache_key_suffix, FileSystemInterface $file_system) {
    // Intentionally does not call parent constructor as this class uses a
    // different YAML discovery.
    parent::__construct('ui_patterns_legacy', $directories);
    $this->discovery = new UIPatternsLegacyDiscovery($directories, $file_cache_key_suffix, $file_system);
  }

}
