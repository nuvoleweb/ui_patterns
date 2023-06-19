<?php

namespace Drupal\ui_patterns_sdc;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

class UiPatternsSdcServiceProvider extends ServiceProviderBase {

  public function alter(ContainerBuilder $container) {

    if ($container->hasDefinition('plugin.manager.sdc')) {
      $definition = $container->getDefinition('plugin.manager.sdc');
        $definition->setClass(
          'Drupal\ui_patterns_sdc\UiPatternsSdcPluginManager'
        );
    }
  }

}
