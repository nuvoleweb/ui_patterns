<?php

namespace Drupal\ui_patterns\Plugin\Deriver;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\TypedData\TypedDataManager;
use Drupal\ui_patterns\Definition\PatternDefinition;
use Drupal\ui_patterns\TypedData\PatternDataDefinition;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AbstractPatternsDeriver.
 *
 * @package Drupal\ui_patterns\Deriver
 */
abstract class AbstractPatternsDeriver extends DeriverBase implements PatternsDeriverInterface, ContainerDeriverInterface {

  /**
   * Typed data manager service.
   *
   * @var \Drupal\Core\TypedData\TypedDataManager
   */
  protected $typedDataManager;

  /**
   * AbstractPatternsDeriver constructor.
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
      $pattern->setDeriver($base_plugin_definition['deriver']);
      $pattern->setClass($base_plugin_definition['class']);
      if ($this->isValidPatternDefinition($pattern)) {
        $this->derivatives[$pattern->id()] = $pattern;
      }
    }
    return $this->derivatives;
  }

  /**
   * Get pattern data object.
   *
   * @param array $definition
   *   Pattern definition array.
   *
   * @return \Drupal\ui_patterns\Definition\PatternDefinition
   *   Pattern definition object.
   */
  protected function getPatternDefinition(array $definition = []) {
    return new PatternDefinition($definition);
  }

  /**
   * Validate pattern definition.
   *
   * @param \Drupal\ui_patterns\Definition\PatternDefinition $definition
   *   Pattern definition.
   *
   * @return bool
   *   Whereas current pattern definition is valid or not.
   */
  protected function isValidPatternDefinition(PatternDefinition $definition) {
    $data_definition = PatternDataDefinition::create();
    $violations = $this->typedDataManager->create($data_definition, $definition->toArray())->validate();
    if ($violations->count()) {
      /** @var \Symfony\Component\Validator\ConstraintViolation $violation */
      drupal_set_message(t("Pattern ':id' is skipped because of the following validation error(s):", [':id' => $definition->id()]), 'error');
      foreach ($violations as $violation) {
        $message = t('Validation error on ":id.:property": :message', [
          ':id' => $definition->id(),
          ':property' => $violation->getPropertyPath(),
          ':message' => $violation->getMessage(),
        ]);
        drupal_set_message($message, 'error');
      }
      return FALSE;
    }
    return TRUE;
  }

}
