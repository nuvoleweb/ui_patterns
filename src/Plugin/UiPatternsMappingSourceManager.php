<?php

namespace Drupal\ui_patterns\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the UI Patterns Mapping Source plugin manager.
 */
class UiPatternsMappingSourceManager extends DefaultPluginManager {

  /**
   * Constructor for UiPatternsMappingSourceManager objects.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/UiPatternsMappingSource', $namespaces, $module_handler, 'Drupal\ui_patterns\Plugin\UiPatternsMappingSourceInterface', 'Drupal\ui_patterns\Annotation\UiPatternsMappingSource');

    $this->alterInfo('ui_patterns_mapping_source_info');
    $this->setCacheBackend($cache_backend, 'ui_patterns_ui_patterns_mapping_source_plugins');
  }

}
