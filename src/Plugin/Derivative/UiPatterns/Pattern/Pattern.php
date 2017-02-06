<?php

namespace Drupal\ui_patterns\Plugin\Derivative\UiPatterns\Pattern;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityManagerInterface;
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
   * The entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $storage;

  /**
   * UiPatternConfig constructor.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entityManager
   *   The entity manager.
   */
  public function __construct(EntityManagerInterface $entityManager) {
    $this->storage = $entityManager->getStorage('ui_patterns_config');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $module = \Drupal::service('module_handler');
    $theme = \Drupal::service('theme_handler');

    $discovery = new UiPatternsDiscovery($module, $theme);

    foreach ($discovery->getDefinitions() as $pattern_id => $pattern) {
      $this->derivatives['pattern_' . $pattern['id']] = $pattern + $base_plugin_definition;
    }

    return $this->derivatives;
  }

}
