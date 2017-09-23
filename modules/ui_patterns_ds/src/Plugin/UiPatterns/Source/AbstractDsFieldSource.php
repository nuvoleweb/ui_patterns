<?php

namespace Drupal\ui_patterns_ds\Plugin\UiPatterns\Source;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\ds\Plugin\DsPluginManager;
use Drupal\ui_patterns\Plugin\PatternSourceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AbstractDsFieldSource.
 *
 * @package Drupal\ui_patterns_ds\Plugin\UiPatterns\Source
 */
abstract class AbstractDsFieldSource extends PatternSourceBase implements ContainerFactoryPluginInterface {

  /**
   * Entity manager service.
   *
   * @var \Drupal\ds\Plugin\DsPluginManager
   */
  protected $dsManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, DsPluginManager $ds_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->dsManager = $ds_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.ds')
    );
  }

}
