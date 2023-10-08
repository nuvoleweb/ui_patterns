<?php

declare(strict_types=1);

namespace Drupal\ui_patterns;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\ui_patterns\Annotation\Source;

/**
 * Source plugin manager.
 */
final class SourcePluginManager extends DefaultPluginManager {

  /**
   * Constructs the object.
   */
  public function __construct(
    \Traversable $namespaces,
    CacheBackendInterface $cache_backend,
    ModuleHandlerInterface $module_handler
  ) {
    parent::__construct(
      'Plugin/UiPatterns/Source',
      $namespaces,
      $module_handler,
      SourceInterface::class,
      Source::class
    );
    $this->alterInfo('ui_patterns_source_info');
    $this->setCacheBackend($cache_backend, 'ui_patterns_source_plugins');
  }

  /**
   *
   */
  public function getSourceDefinitions($prop_type_id) {
    $definitions = $this->getDefinitions();
    $sources = [];
    foreach ($definitions as $definition) {
      if (isset($definition['prop_types']) && in_array(
          $prop_type_id,
          $definition['prop_types']
        )) {
        $sources[] = $definition;
      }
    }
    return $sources;
  }

  /**
   *
   */
  public function getSourcePlugins($prop_type_id, $prop_id, $prop_definition):array {
    $definitions = $this->getSourceDefinitions($prop_type_id);
    $sources = [];
    foreach ($definitions as $definition) {
      $sources[] = $this->createInstance(
        $definition['id'],
        [
          'prop_id' => $prop_id,
          'prop_definition' => $prop_definition,
        ]
      );
    }
    return $sources;
  }

}
