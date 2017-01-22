<?php

namespace Drupal\ui_patterns\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ui_patterns\UiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PatternLibraryController.
 *
 * @package Drupal\ui_patterns\Controller
 */
class PatternLibraryController extends ControllerBase {

  /**
   * Patterns manager service.
   *
   * @var \Drupal\ui_patterns\UiPatternsManager
   */
  protected $patternsManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(UiPatternsManager $ui_patterns_manager) {
    $this->patternsManager = $ui_patterns_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('plugin.manager.ui_patterns'));
  }

  /**
   * Title callback.
   *
   * @return string
   *   Pattern label.
   */
  public function title($name) {
    $definition = $this->patternsManager->getDefinition($name);
    return $definition['label'];
  }

  /**
   * Render pattern library page.
   *
   * @return array
   *   Return render array.
   */
  public function single($name) {

    $definition = $this->patternsManager->getDefinition($name);
    $definition['rendered']['#type'] = 'pattern_preview';
    $definition['rendered']['#id'] = $name;
    $definition['meta']['#theme'] = 'patterns_meta_information';
    $definition['meta']['#pattern'] = $definition;

    return [
      '#theme' => 'patterns_single_page',
      '#pattern' => $definition,
    ];
  }

  /**
   * Render pattern library page.
   *
   * @return array
   *   Patterns overview page render array.
   */
  public function overview() {

    $definitions = $this->patternsManager->getDefinitions();
    foreach ($definitions as $name => $definition) {
      $definitions[$name]['rendered']['#type'] = 'pattern_preview';
      $definitions[$name]['rendered']['#id'] = $name;
      $definitions[$name]['meta']['#theme'] = 'patterns_meta_information';
      $definitions[$name]['meta']['#pattern'] = $definition;
    }

    return [
      '#theme' => 'patterns_overview_page',
      '#patterns' => $definitions,
    ];
  }

}
