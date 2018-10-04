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
   *   Plugin ID.
   *
   * @return array
   *   Return render array.
   */
  public function single($name) {
    /** @var \Drupal\ui_patterns\Definition\PatternDefinition $definition */

    $definition = [];
    $definition['meta']['#theme'] = 'patterns_meta_information';
    $definition['meta']['#pattern'] = $this->patternsManager->getDefinition($name)->toArray();

    if ($this->patternsManager->getDefinition($name)->hasVariants()) {
      $variants = $this->patternsManager->getDefinition($name)->getVariants();
      $definition['meta']['#variant'] = $variants;

      foreach ($variants as $id => $variant) {
        $preview = [];
        $preview['rendered']['#type'] = 'pattern_preview';
        $preview['rendered']['#id'] = $name;
        $preview['rendered']['#variant'] = $id;
        $preview['meta']['#variant'] = $variant->toArray();

        $definition['previews'][$id] = $preview;
      }
    }
    else {
      $preview = [];
      $preview['rendered']['#type'] = 'pattern_preview';
      $preview['rendered']['#id'] = $name;

      $definition['previews'][] = $preview;
    }

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
    foreach ($this->patternsManager->getDefinitions() as $id_pattern => $definition) {
      $definitions[$id_pattern] = $definition->toArray();
      $definitions[$id_pattern]['meta']['#theme'] = 'patterns_meta_information';
      $definitions[$id_pattern]['meta']['#pattern'] = $this->patternsManager->getDefinition($id_pattern)->toArray();

      if ($this->patternsManager->getDefinition($id_pattern)->hasVariants()) {
        $variants = $this->patternsManager->getDefinition($id_pattern)->getVariants();
        $definitions[$id_pattern]['meta']['#variant'] = $variants;

        foreach ($variants as $id_variant => $variant) {
          $preview = [];
          $preview['rendered']['#type'] = 'pattern_preview';
          $preview['rendered']['#id'] = $id_pattern;
          $preview['rendered']['#variant'] = $id_variant;
          $preview['meta']['#variant'] = $variant->toArray();

          $definitions[$id_pattern]["previews"][$id_variant] = $preview;
        }
      }
      else {
        $preview = [];
        $preview['rendered']['#type'] = 'pattern_preview';
        $preview['rendered']['#id'] = $id_pattern;

        $definitions[$id_pattern]["previews"][] = $preview;
      }
    }

    return [
      '#theme' => 'patterns_overview_page',
      '#patterns' => $definitions,
    ];
  }

}
