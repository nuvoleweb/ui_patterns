<?php

namespace Drupal\ui_patterns\Plugin\Derivative\UiPatterns\Pattern;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\ui_patterns\Discovery\UiPatternsDiscovery;
use Drupal\ui_patterns\Discovery\YamlDiscovery;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Pattern.
 *
 * @package Drupal\ui_patterns\Plugin\Derivative\UiPatterns\Pattern
 */
class Pattern extends DeriverBase implements ContainerDeriverInterface {

  /**
   * Pattern constructor.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   The theme handler service.
   */
  public function __construct(ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler) {
    $this->moduleHandler = $module_handler;
    $this->themeHandler = $theme_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('module_handler'),
      $container->get('theme_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $discovery = new UiPatternsDiscovery($this->moduleHandler, $this->themeHandler);

    foreach ($discovery->getDefinitions() as $pattern_id => $pattern) {
      $this->derivatives[$pattern_id] = $pattern + $base_plugin_definition;
    }

    return $this->derivatives;
  }

}
