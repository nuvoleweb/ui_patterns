<?php

namespace Drupal\ui_patterns_library\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ui_patterns\UiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PatternLibraryController.
 *
 * @package Drupal\ui_patterns\Controller
 */
class PatternsLibraryController extends ControllerBase {

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
    return $this->patternsManager->getDefinition($name)->getLabel();
  }

  /**
   * Render pattern library page.
   *
   * @param string $name
   *    Plugin ID.
   *
   * @return array
   *   Return render array.
   */
  public function single($name) {
    $definition = [];
    $definition['rendered']['#type'] = 'pattern_preview';
    $definition['rendered']['#id'] = $name;
    $definition['meta']['#theme'] = 'patterns_meta_information';
    $definition['meta']['#pattern'] = $this->patternsManager->getDefinition($name)->toArray();

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
    /** @var \Drupal\ui_patterns\Definition\PatternDefinition $definition */

    $definitions = [];
    foreach ($this->patternsManager->getDefinitions() as $id => $definition) {
      $definitions[$id] = $definition->toArray();
      $definitions[$id]['rendered']['#type'] = 'pattern_preview';
      $definitions[$id]['rendered']['#id'] = $definition->id();
      $definitions[$id]['meta']['#theme'] = 'patterns_meta_information';
      $definitions[$id]['meta']['#pattern'] = $definition->toArray();
    }

    return [
      '#theme' => 'patterns_overview_page',
      '#patterns' => $definitions,
    ];
  }

}
