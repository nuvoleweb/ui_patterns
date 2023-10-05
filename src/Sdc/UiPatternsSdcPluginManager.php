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
    foreach ($definitions as & $definition) {
      foreach ($definition['props']['properties'] as $prop_id => & $prop)  {
        $prop_type = $this->propTypePluginManager->getPropType($prop);
        $prop['type_definition'] = $prop_type?->label() ?? 'undefined';
      }
    }
  }
}
