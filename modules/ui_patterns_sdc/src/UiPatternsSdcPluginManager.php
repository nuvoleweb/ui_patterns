<?php


namespace Drupal\ui_patterns_sdc;

use Drupal\sdc\ComponentPluginManager;
use Drupal\sdc\Plugin\Discovery\DirectoryWithMetadataPluginDiscovery;
use Drupal\ui_patterns\UiPatterns;
use Drupal\ui_patterns\UiPatternsManager;
use Drupal\ui_patterns_sdc\Plugin\Discovery\UiPatternsSdcPluginDiscovery;

/**
 * Defines a plugin manager to deal with sdc.
 *
 * Modules and themes can create components by adding a folder under
 * MODULENAME/components/my-component/my-component.sdc.yml.
 *
 * @see plugin_api
 *
 * @internal
 */
final class UiPatternsSdcPluginManager extends ComponentPluginManager {

  /**
   * {@inheritdoc}
   */
  protected function getDiscovery() {
    if (!isset($this->discovery)) {
      $directories = $this->getScanDirectories();
      $decorated = new DirectoryWithMetadataPluginDiscovery($directories, 'sdc', $this->fileSystem);
      $this->discovery = new UiPatternsSdcPluginDiscovery(
        $decorated,
        $directories,
        'sdc',
        $this->fileSystem
      );
    }
    return $this->discovery;
  }


  protected function isUiPatternFile($definition){
    return isset($definition['_discovered_file_path']) && str_ends_with($definition['_discovered_file_path'], 'ui_patterns.yml');
  }

  protected function mapPatternToComponent($pattern, $component) {
    $component['props'] = ['type' => 'object', 'properties' => []];
    if (isset($pattern['fields'])) {
      foreach ($pattern['fields'] as $field_id => $field) {
        $component['slots'][$field_id] = [
          'title' => $field['label'],
          'description' => $field['description'] ?? NULL,
          'examples' => $field['preview'] ?? NULL
        ];
      }
    }
    return $component;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterDefinitions(&$definitions) {
    foreach ($definitions as & $definition) {
      $id = $definition['id']  ?? NULL;
      if ($id) {
        $definition_patterns_id = explode(':', $id)[1] ?? NULL;
        if ($this->isUiPatternFile($definition)) {
          $patterns = [];
          // First detect UI Patterns definitions.
          foreach ($definition as $key => $ui_pattern_definition) {
            // Simple check for sub key label?
            // Not sure if there is something better.
            if (isset($ui_pattern_definition['label'])) {
              $patterns[$key] = $definition[$key];
              unset($definition[$key]);
            }
          }

          // Components only accept one component for one file
          // To support multiple components for one file we clone them.
          foreach ($patterns as $pattern_id => $pattern) {
            if ($definition_patterns_id !== $pattern_id) {
              $cloned_definition = $definition;
              $cloned_definition[] = '';
              $cloned_definition_id = $cloned_definition['provider'] . ':' . $pattern_id;
              $cloned_definition['id'] = $cloned_definition_id;
              $definitions[$cloned_definition_id] = $this->mapPatternToComponent($pattern, $cloned_definition);
            } else {
              $definitions[$id] = $this->mapPatternToComponent($pattern, $definition);
            }
            $definitions[$id]['ui_pattern_id'] = $pattern_id;
          }
        }
      }

    }
    return parent::alterDefinitions($definitions);
  }

}
