<?php

namespace Drupal\ui_patterns\Plugin\Deriver;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\TypedData\TypedDataManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AbstractDeriver.
 *
 * @package Drupal\ui_patterns\Deriver
 */
abstract class AbstractDeriver extends DeriverBase implements DeriverInterface, ContainerDeriverInterface {

  /**
   * Typed data manager service.
   *
   * @var \Drupal\Core\TypedData\TypedDataManager
   */
  protected $typedDataManager;

  /**
   * Default pattern data definition type.
   *
   * @var string
   */
  protected $dataType = 'ui_patterns_pattern';

  /**
   * AbstractDeriver constructor.
   *
   * @param string $base_plugin_id
   *   The base plugin ID.
   * @param \Drupal\Core\TypedData\TypedDataManager $typed_data_manager
   *   Typed data manager service.
   */
  public function __construct($base_plugin_id, TypedDataManager $typed_data_manager) {
    $this->basePluginId = $base_plugin_id;
    $this->typedDataManager = $typed_data_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $base_plugin_id,
      $container->get('typed_data_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    foreach ($this->getPatterns() as $pattern) {
      $id = $pattern->get('id')->getString();
      if ($pattern->isValid()) {
        $this->derivatives[$id] = $pattern->toArray() + $base_plugin_definition;
      }
      else {
        drupal_set_message(t("Pattern ':id' is skipped because of the following validation error(s):", [':id' => $id]), 'error');
        foreach ($pattern->getErrorMessages() as $message) {
          drupal_set_message($message, 'error');
        }
      }
    }
    return $this->derivatives;
  }

  /**
   * Get pattern data object.
   *
   * @param mixed $definition
   *    Pattern definition array.
   *
   * @return \Drupal\ui_patterns\Plugin\DataType\Pattern
   *    Pattern definition object.
   */
  protected function getPattern($definition = NULL) {
    $data_definition = $this->typedDataManager->createDataDefinition($this->dataType);
    return $this->typedDataManager->create($data_definition, $definition);
  }

}
