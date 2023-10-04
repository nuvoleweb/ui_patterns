<?php

namespace Drupal\ui_patterns_library\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\sdc\ComponentPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LibraryController.
 *
 * @package Drupal\ui_patterns_library\Controller
 */
class LibraryController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function __construct(protected ComponentPluginManager $componentPluginManager) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.sdc')
    );
  }

  /**
   * Title callback.
   *
   * @return string
   *   Pattern label.
   */
  public function title($name) {
    $definition = $this->componentPluginManager->getDefinition($name);
    return $definition["name"];
  }

  /**
   * Render a single component page.
   *
   * @param string $name
   *   Plugin ID.
   *
   * @return array
   *   Return render array.
   */
  public function single($name) {
    $definition = $this->componentPluginManager->getDefinition($name);
    return [
      '#theme' => 'patterns_single_page',
      '#component' => $definition,
    ];
  }

  /**
   * Render the components overview page.
   *
   * @return array
   *   Patterns overview page render array.
   */
  public function overview() {
    // @todo use UI Patterns plugin maanger instead of SDC, because we look for categorized definitions.
    $definitions = $this->componentPluginManager->getAllComponents();
    $categorized_definitions = [
      "all" => $definitions,
    ];
    return [
      '#theme' => 'patterns_overview_page',
      '#components' => $categorized_definitions,
    ];
  }

}
