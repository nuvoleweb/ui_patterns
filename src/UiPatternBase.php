<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UiPatternBase.
 *
 * @package Drupal\ui_patterns
 */
abstract class UiPatternBase extends PluginBase implements UiPatternInterface, ContainerFactoryPluginInterface {

  /**
   * Application root.
   *
   * @var string
   */
  protected $root;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * Twig loader service.
   *
   * @var \Twig_LoaderInterface
   */
  protected $twigLoader;

  /**
   * UiPatternBase constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param string $root
   *   The application root directory.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   The theme handler service.
   * @param \Twig_Loader_Filesystem $twig_loader
   *   The twig loader service.
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, $root, ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler, \Twig_Loader_Filesystem $twig_loader) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->moduleHandler = $module_handler;
    $this->themeHandler = $theme_handler;
    $this->twigLoader = $twig_loader;
    $this->root = $root;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('app.root'),
      $container->get('module_handler'),
      $container->get('theme_handler'),
      $container->get('twig.loader.filesystem')
    );
  }

}
