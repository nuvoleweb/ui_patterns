<?php

namespace Drupal\ui_patterns\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the UI Patterns Source plugin manager.
 */
class UiPatternsSourceManager extends DefaultPluginManager {

  /**
   * Constructor for UiPatternsSourceManager objects.
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
    parent::__construct('Plugin/UiPatterns/Source', $namespaces, $module_handler, 'Drupal\ui_patterns\Plugin\UiPatternsSourceInterface', 'Drupal\ui_patterns\Annotation\UiPatternsSource');

    $this->alterInfo('ui_patterns_ui_patterns_source_info');
    $this->setCacheBackend($cache_backend, 'ui_patterns_ui_patterns_source_plugins');
  }

  /**
   * Filter definitions by given tag.
   *
   * @param string $tag
   *    Tag used on plugin annotation.
   *
   * @return array
   *    List of definitions tagged with given tag.
   */
  public function getDefinitionsByTag($tag) {
    return array_filter($this->getDefinitions(), function ($definition) use ($tag) {
      return in_array($tag, $definition['tags']);
    });
  }

}
