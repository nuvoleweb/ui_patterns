<?php

namespace Drupal\ui_patterns_legacy;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Replace SDC Plugin Manager service with our own.
 */
class UiPatternsLegacyServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    return;
    if ($container->hasDefinition('plugin.manager.sdc')) {
      $definition = $container->getDefinition('plugin.manager.sdc');
      $definition->setClass(
          'Drupal\ui_patterns_legacy\UiPatternsLegacyPluginManager'
        );
    }
  }

}
