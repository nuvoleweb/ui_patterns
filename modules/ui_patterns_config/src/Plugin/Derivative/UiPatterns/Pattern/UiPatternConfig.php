<?php

namespace Drupal\ui_patterns_config\Plugin\Derivative\UiPatterns\Pattern;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UiPatternConfig.
 *
 * @package Drupal\ui_patterns_config\Plugin\Derivative\UiPatterns\Pattern
 */
class UiPatternConfig extends DeriverBase implements ContainerDeriverInterface {

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
    foreach ($this->storage->loadMultiple() as $pattern_id => $pattern) {
      $this->derivatives[$pattern_id] = $pattern->getProcessedDefinition() + $base_plugin_definition;
    }
    return $this->derivatives;
  }

}
