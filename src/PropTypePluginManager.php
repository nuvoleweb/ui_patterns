<?php

namespace Drupal\ui_patterns;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\sdc\Component\SchemaCompatibilityChecker;
use Drupal\sdc\Exception\IncompatibleComponentSchema;

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
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler, protected SchemaCompatibilityChecker $compatibilityChecker) {
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
  public function getPropType(string $prop_id, array $prop_schema): ?PropTypeInterface {
    $definition = $this->getPropTypeDefinition($prop_id, $prop_schema);
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
  public function getPropTypeDefinition(string $prop_id, array $prop_schema): ?array {
    $definitions = $this->getSortedDefinitions();
    foreach ($definitions as $definition) {
      $annotation_schema['properties'][$prop_id] = $definition['schema'];
      $mapped_prop_schema['properties'][$prop_id] = $prop_schema;
      try {
        $this->compatibilityChecker->isCompatible($mapped_prop_schema, $annotation_schema);
        return $definition;
      }
      catch (IncompatibleComponentSchema $exception) {
        // Do nothing.
      }
    }
    return NULL;
  }

}
