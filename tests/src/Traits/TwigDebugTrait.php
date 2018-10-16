<?php

namespace Drupal\Tests\ui_patterns\Traits;

/**
 * Provides shortcut to enable Twig debug mode on \Drupal\Tests\BrowserTestBase.
 */
trait TwigDebugTrait {

  /**
   * Enable Twig debug mode.
   */
  public function enableTwigDebugMode() {
    // Enable debug, rebuild the service container, and clear all caches.
    $parameters = $this->container->getParameter('twig.config');
    $parameters['debug'] = TRUE;
    $this->setContainerParameter('twig.config', $parameters);
    $this->rebuildContainer();
    $this->resetAll();
  }

}
