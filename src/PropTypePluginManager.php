<?php

namespace Drupal\ui_patterns;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\ui_patterns\Utils\SchemaCompatibilityChecker;

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
    $this->setCacheBackend($cache_backend, 'ui_patterns_prop_type_plugins');
  }

  /**
   *
   */
  public function getPropType(array $prop_schema): ?PropTypeInterface {
    $definition = $this->getPropTypeDefinition($prop_schema);
    if ($definition !== NULL) {
      return $this->createInstance($definition['id'], []);
    }
    return NULL;
  }

  /**
   *
   */
  public function getSortedDefinitions() {
    $definitions = $this->getDefinitions();
    usort($definitions, function ($a, $b) {
      return $a['priority'] ?? 1 > $b['priority'] ?? 1;
    });
    return $definitions;
  }

  /**
   *
   */
  public function getPropTypeDefinition(array $prop_schema): ?array {
    if (isset($prop_schema['$ref']) && str_contains($prop_schema['$ref'], "ui-patterns://")) {
      $prop_type_id = str_replace("ui-patterns://", "", $prop_schema['$ref']);
      return $this->getDefinition($prop_type_id);
    }
    $definitions = $this->getSortedDefinitions();
    foreach ($definitions as $definition) {
      $compatibilityChecker = new SchemaCompatibilityChecker();
      if ($compatibilityChecker->isCompatible($definition['schema'], $prop_schema)) {
        return $definition;
      }
    }
    return NULL;
  }

}
