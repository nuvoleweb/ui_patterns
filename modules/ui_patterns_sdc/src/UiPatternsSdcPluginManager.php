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

  /**
   * {@inheritdoc}
   */
  protected function alterDefinitions(&$definitions) {
    $ui_patterns_definitions = UiPatterns::getPatternDefinitions();
    foreach ($definitions as & $definition) {
      $id = $definition['id']  ?? NULL;
      if ($id) {
        $ui_patterns_id = explode(':', $id)[1] ?? NULL;
        if ($pattern = $ui_patterns_definitions[$ui_patterns_id] ?? NULL) {

          // Check if the pattern is created by the sdc discover.
          if (isset($pattern->getAdditional()['sdc'])) {
            continue;
          }
          //$definition['$schema'] = 'https://git.drupalcode.org/project/sdc/-/raw/1.x/src/metadata.schema.json';
          $definition['props'] = ['type' => 'object', 'properties' => []];
          foreach ($pattern->getFields() as $field_name => $field) {
            $definition['slots'][$field_name] = [
              'title' => $field->getLabel(),
              'description' => $field->getDescription(),
              'examples' => $field->getPreview()
            ];
          }
        }
      }

    }
    return parent::alterDefinitions($definitions);
  }

}
