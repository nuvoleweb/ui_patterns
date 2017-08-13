<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\Factory\DefaultFactory;
use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides the UI Patterns Settings plugin manager.
 */
class UiPatternsSettingsManager extends DefaultPluginManager implements PluginManagerInterface {

  use StringTranslationTrait;

  /**
   * UiPatternsSettingsManager constructor.
   */
  public function __construct(\Traversable $namespaces, ModuleHandlerInterface $module_handler, CacheBackendInterface $cache_backend) {
    parent::__construct('Plugin/UiPatterns/SettingType', $namespaces, $module_handler, 'Drupal\ui_patterns\SettingTypeInterface', 'Drupal\ui_patterns\Annotation\UiPatternsSettingType');
    $this->moduleHandler = $module_handler;
    $this->alterInfo('ui_patterns_settings_info');
    $this->setCacheBackend($cache_backend, 'ui_patterns_settings', ['ui_patterns_settings']);
  }

  /**
   * {@inheritdoc}
   */
  public function createInstance($plugin_id, array $configuration = []) {
    $plugin_definition = $this->getDefinition($plugin_id);
    $plugin_class = DefaultFactory::getPluginClass($plugin_id, $plugin_definition);
    // If the plugin provides a factory method, pass the container to it.
    if (is_subclass_of($plugin_class, 'Drupal\Core\Plugin\ContainerFactoryPluginInterface')) {
      $plugin = $plugin_class::create(\Drupal::getContainer(), $configuration, $plugin_id, $plugin_definition);
    }
    else {
      $plugin = new $plugin_class($configuration, $plugin_id, $plugin_definition);
    }
    return $plugin;
  }

}
