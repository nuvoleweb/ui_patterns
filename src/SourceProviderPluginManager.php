<?php declare(strict_types = 1);

namespace Drupal\ui_patterns;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\sdc\Component\ComponentMetadata;
use Drupal\sdc\Plugin\Component;
use Drupal\ui_patterns\Annotation\SourceProvider;

/**
 * SourceProvider plugin manager.
 */
final class SourceProviderPluginManager extends DefaultPluginManager {

  /**
   * Constructs the object.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/UiPatterns/SourceProvider', $namespaces, $module_handler, SourceProviderInterface::class, SourceProvider::class);
    $this->alterInfo('source_provider_info');
    $this->setCacheBackend($cache_backend, 'ui_patterns_source_provider_plugins');
  }

  public function getSourceProviders(PropTypeInterface $prop_type) {
    $definitions = $this->getDefinitions();
  }
}
