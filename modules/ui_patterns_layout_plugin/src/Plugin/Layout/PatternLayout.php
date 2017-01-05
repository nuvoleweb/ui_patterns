<?php

namespace Drupal\ui_patterns_layout_plugin\Plugin\Layout;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\layout_plugin\Plugin\Layout\LayoutBase;
use Drupal\ui_patterns\UiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LayoutDefault.
 *
 * @package Drupal\layout_plugin\Plugin\Layout
 */
class PatternLayout extends LayoutBase implements ContainerFactoryPluginInterface {

  /**
   * Pattern manager service.
   *
   * @var \Drupal\ui_patterns\UiPatternsManager
   */
  protected $patternManager = NULL;

  /**
   * Constructs a LocalActionDefault object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\ui_patterns\UiPatternsManager $pattern_manager
   *    Pattern manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UiPatternsManager $pattern_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->patternManager = $pattern_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.ui_patterns')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);

    // Patterns expect regions to be passed along in a render array fashion.
    foreach ($regions as $region_name => $region) {
      $build["#$region_name"] = $build[$region_name];
    }
    return $build;
  }

}
