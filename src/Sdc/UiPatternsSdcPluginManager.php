<?php

namespace Drupal\ui_patterns\Sdc;

/**
 * Plugin Manager for....
 *
 * @see plugin_api
 *
 * @internal
 */
class UiPatternsSdcPluginManager extends ComponentPluginManagerDecorator {

  /**
   * {@inheritdoc}
   */
  protected function getCacheKey() {
    return 'ui_patterns';
  }

  /**
   * {@inheritdoc}
   */
  protected function alterDefinitions(&$definitions) {
    parent::alterDefinitions($definitions);
    $definitions = $this->addPropTypes($definitions);
  }

  /**
   *
   */
  protected function addPropTypes($definitions) {
    foreach ($definitions as $component_id => $definition) {
      if (!isset($definition['props'])) {
        continue;
      }
      if (!isset($definition['props']['properties'])) {
        continue;
      }
      foreach ($definition['props']['properties'] as $prop_id => $prop) {
        $prop_type = $this->propTypePluginManager->getPropType($prop_id, $prop);
        $prop['type_definition'] = $prop_type?->label() ?? 'undefined';
        $definition['props']['properties'][$prop_id] = $prop;
      }
      $definitions[$component_id] = $definition;
    }
    return $definitions;
  }

}
