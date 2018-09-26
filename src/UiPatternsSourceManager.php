<?php

namespace Drupal\ui_patterns;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the UI Patterns Source plugin manager.
 */
class UiPatternsSourceManager extends DefaultPluginManager {

  /**
   * Constructor for UiPatternsSourceManager objects.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/UiPatterns/Source', $namespaces, $module_handler, 'Drupal\ui_patterns\Plugin\PatternSourceInterface', 'Drupal\ui_patterns\Annotation\UiPatternsSource');
    $this->alterInfo('ui_patterns_ui_patterns_source_info');
    $this->setCacheBackend($cache_backend, 'ui_patterns_ui_patterns_source_plugins');
  }

  /**
   * Filter definitions by given tag.
   *
   * @param string $tag
   *   Tag used on plugin annotation.
   *
   * @return array
   *   List of definitions tagged with given tag.
   */
  public function getDefinitionsByTag($tag) {
    return array_filter($this->getDefinitions(), function ($definition) use ($tag) {
      return in_array($tag, $definition['tags']);
    });
  }

  /**
   * Get field source definitions by specified tags.
   *
   * @param string $tag
   *   Field source tag.
   * @param array $context
   *   Plugin context.
   *
   * @return \Drupal\ui_patterns\Definition\PatternSourceField[]
   *   List of source fields.
   */
  public function getFieldsByTag($tag, array $context) {
    /** @var \Drupal\ui_patterns\Plugin\PatternSourceInterface $plugin */
    $fields = [];
    foreach ($this->getDefinitionsByTag($tag) as $id => $definition) {
      $plugin = $this->createInstance($id, ['context' => $context]);
      foreach ($plugin->getSourceFields() as $field) {
        $fields[$field->getFieldKey()] = $field;
      }
    }

    return $fields;
  }

}
