<?php

namespace Drupal\ui_patterns\Sdc;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\sdc\Component\ComponentValidator;
use Drupal\sdc\Component\SchemaCompatibilityChecker;
use Drupal\sdc\ComponentPluginManager;
use Drupal\sdc\Plugin\Component;
use Drupal\ui_patterns\PropTypePluginManager;

/**
 * Plugin Manager for *.ui_patterns.yml configuration files.
 *
 * Plugin Manager overwrites getDiscovery() to provide a decorated
 * Discovery. Decoration of the service seems not possible for me.
 * Probably there is more gentle way.
 *
 * @see plugin_api
 *
 * @internal
 */
abstract class ComponentPluginManagerDecorator extends ComponentPluginManager {

  public function __construct(
    protected ComponentPluginManager $parentSdcPluginManager,
    protected PropTypePluginManager $propTypePluginManager,
    ModuleHandlerInterface $module_handler,
    ThemeHandlerInterface $themeHandler,
    CacheBackendInterface $cacheBackend,
    ConfigFactoryInterface $configFactory,
    ThemeManagerInterface $themeManager,
    \Drupal\sdc\ComponentNegotiator $componentNegotiator,
    FileSystemInterface $fileSystem,
    SchemaCompatibilityChecker $compatibilityChecker,
    ComponentValidator $componentValidator,
    string $appRoot,
  ) {
    parent::__construct(
      $module_handler,
      $themeHandler,
      $cacheBackend,
      $configFactory,
      $themeManager,
      $componentNegotiator,
      $fileSystem,
      $compatibilityChecker,
      $componentValidator,
      $appRoot
    );
    $this->setCacheBackend($cacheBackend, $this->getCacheKey());
  }

  public function createInstance($plugin_id, array $configuration = []): Component {
    if (parent::hasDefinition($plugin_id)) {
      return parent::createInstance($plugin_id, $configuration);
    }
    else {
      return $this->parentSdcPluginManager->createInstance($plugin_id, $configuration);
    }
  }

  /**
   * Returns the cache key for the decorated service.
   *
   * @return string
   *   The cache key.
   */
  protected abstract function getCacheKey();

  /**
   * {@inheritdoc}
   */
  public function find(string $component_id): Component {
    if (parent::hasDefinition($component_id)) {
      return parent::find($component_id);
    }
    return $this->parentSdcPluginManager->find($component_id);
  }

  /**
   * {@inheritdoc}
   */
  public function getAllComponents(): array {
    $original_components = $this->parentSdcPluginManager->getAllComponents();
    return array_merge($original_components, parent::getAllComponents());
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitions() {
    $decorated_definitions = parent::getDefinitions();
    $original_definitions = $this->parentSdcPluginManager->getDefinitions();
    return array_merge($original_definitions, $decorated_definitions);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinition($plugin_id, $exception_on_invalid = TRUE) {
    $original_definition = parent::getDefinition($plugin_id, FALSE);
    return $original_definition ?? $this->parentSdcPluginManager->getDefinition($plugin_id, $exception_on_invalid);
  }

}
