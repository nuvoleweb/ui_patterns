<?php

namespace Drupal\ui_patterns_props_widget;

use Drupal\Component\Plugin\Factory\DefaultFactory;
use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\sdc\Component\ComponentMetadata;
use Drupal\sdc\Component\SchemaCompatibilityChecker;
use Drupal\ui_patterns_props_widget\Element\ComponentPropsWidget;

/**
 * Provides the UI Patterns Settings plugin manager.
 */
class UiPatternsPropsWidgetManager extends DefaultPluginManager implements PluginManagerInterface {

  use StringTranslationTrait;

  /**
   * UiPatternsSettingsManager constructor.
   */
  public function __construct(\Traversable $namespaces, ModuleHandlerInterface $module_handler, CacheBackendInterface $cache_backend, protected SchemaCompatibilityChecker $compatibilityChecker) {
    parent::__construct('Plugin/UiPatterns/PropWidget', $namespaces, $module_handler, 'Drupal\ui_patterns_props_widget\PropsWidgetInterface', 'Drupal\ui_patterns_props_widget\Annotation\PropWidget');
    $this->moduleHandler = $module_handler;
    $this->alterInfo('ui_patterns_props_widget_info');
    $this->setCacheBackend($cache_backend, 'ui_patterns_props_widget', ['ui_patterns_props_widget']);
  }

  /**
   *
   */
  public function getWidgetByProp(ComponentMetadata $component_metadata, $prop_name, array $prop) {
    $definitions = $this->getDefinitions();
    $widget = NULL;
    $metadata_schema = $component_metadata->schema;
    // Check for an existing widget.
    if (isset($prop['widget']['type'])) {
      $widget_type = $prop['widget']['type'];
      $widget = $this->getDefinition($widget_type);
    }

    // Check for default widgets.
    $schema_stub = ['name' => $prop_name, 'props' => ['properties' => []]];

    foreach ($definitions as $definition) {
      $origin_schema = $schema_stub;
      $origin_schema['props']['properties'][$prop_name] = $metadata_schema['props']['properties'][$prop_name];
      $annotation_schema = $origin_schema;
      $annotation_schema['props']['properties'][$prop_name] = $definition['applies'];
      if ($this->compatibilityChecker->isCompatible($origin_schema, $annotation_schema)) {
        $widget = $definition;
        break;
      }
    }
    return $widget;
  }

  /**
   * Returns TRUE if a variant token can configured.
   *
   * @param \Drupal\sdc\Component\ComponentMetadata $component_metadata
   *   The pattern definition.
   *
   * @return bool
   *   Returns TRUE if a variant token can configured.
   */
  public static function allowVariantToken(ComponentMetadata $component_metadata) {
    if (isset($component_metadata->allow_variant_token) && $component_metadata->allow_variant_token) {
      return TRUE;
    }
    else {
      return FALSE;
    }
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
