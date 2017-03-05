<?php

namespace Drupal\ui_patterns\Plugin\DataType;

use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\ListDataDefinition;
use Drupal\Core\TypedData\MapDataDefinition;

/**
 * Class PatternDefinition.
 *
 * @package Drupal\ui_patterns\Plugin\DataType
 */
class PatternDefinition extends MapDataDefinition {

  /**
   * Valid machine name string.
   */
  const MACHINE_NAME = '/^[a-z0-9_]+$/';

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions() {
    $this->setMainPropertyName('id')
      ->setPropertyDefinition('id', $this->getMachineNameDefinition()->setRequired(TRUE))
      ->setPropertyDefinition('label', DataDefinition::create('string')->setRequired(TRUE))
      ->setPropertyDefinition('description', DataDefinition::create('string'))
      ->setPropertyDefinition('tags', ListDataDefinition::create('string'))
      ->setPropertyDefinition('variants', $this->getVariantDefinition())
      ->setPropertyDefinition('fields', $this->getFieldsDefinition()->setRequired(TRUE));
    return $this->propertyDefinitions;
  }

  /**
   * Get valid machine name definition.
   *
   * @return \Drupal\Core\TypedData\DataDefinition
   *    Data definition instance.
   */
  protected function getMachineNameDefinition() {
    return DataDefinition::create('string')
      ->addConstraint('Regex', self::MACHINE_NAME)
      ->addConstraint('ValidPatternMachineName');
  }

  /**
   * Get definition for 'variants' property.
   *
   * @return \Drupal\Core\TypedData\ListDataDefinition
   *    Data definition instance.
   */
  protected function getVariantDefinition() {
    return new ListDataDefinition([], MapDataDefinition::create()
      ->setPropertyDefinition('name', $this->getMachineNameDefinition()->setRequired(TRUE))
      ->setPropertyDefinition('label', DataDefinition::create('string')->setRequired(TRUE))
      ->setPropertyDefinition('description', DataDefinition::create('string')));
  }

  /**
   * Get definition for 'field' property.
   *
   * @return \Drupal\Core\TypedData\ListDataDefinition
   *    Data definition instance.
   */
  protected function getFieldsDefinition() {
    return new ListDataDefinition([], MapDataDefinition::create()
      ->setPropertyDefinition('name', $this->getMachineNameDefinition()->setRequired(TRUE))
      ->setPropertyDefinition('type', $this->getMachineNameDefinition()->setRequired(TRUE))
      ->setPropertyDefinition('label', DataDefinition::create('string')->setRequired(TRUE))
      ->setPropertyDefinition('description', DataDefinition::create('string'))
      ->setPropertyDefinition('preview', DataDefinition::create('any')));
  }

}
