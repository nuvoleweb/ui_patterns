<?php

namespace Drupal\ui_patterns;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * PropType plugin manager.
 */
class PropTypePluginManager extends DefaultPluginManager {

  /**
   * Constructs PropTypePluginManager object.
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
    parent::__construct(
      'Plugin/UiPatterns/PropType',
      $namespaces,
      $module_handler,
      'Drupal\ui_patterns\PropTypeInterface',
      'Drupal\ui_patterns\Annotation\PropType'
    );
    $this->alterInfo('prop_type_info');
    $this->setCacheBackend($cache_backend, 'prop_type_plugins');
  }

}
