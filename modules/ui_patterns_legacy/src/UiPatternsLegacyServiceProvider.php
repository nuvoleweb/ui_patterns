<?php

namespace Drupal\ui_patterns_legacy;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

class UiPatternsLegacyServiceProvider extends ServiceProviderBase {

  public function alter(ContainerBuilder $container) {

    if ($container->hasDefinition('plugin.manager.sdc')) {
      $definition = $container->getDefinition('plugin.manager.sdc');
        $definition->setClass(
          'Drupal\ui_patterns_legacy\UiPatternsLegacyPluginManager'
        );
    }
  }

}
