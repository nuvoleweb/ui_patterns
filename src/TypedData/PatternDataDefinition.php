<?php

namespace Drupal\ui_patterns\TypedData;

use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\ListDataDefinition;
use Drupal\Core\TypedData\MapDataDefinition;

/**
 * Class PatternDefinition.
 *
 * @package Drupal\ui_patterns\Plugin\DataType
 */
class PatternDataDefinition extends MapDataDefinition {

  /**
   * Valid machine name string.
   */
  const MACHINE_NAME = '/^(?!(%s)$)(?=[A-Za-z0-9_]+$).*$/';

  /**
   * Reserved words.
   *
   * @var array
   */
  protected $reserved = [
    'id',
    'type',
    'theme',
    'context',
    'use',
    'attributes',
  ];

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions() {
    $this->setMainPropertyName('id')
      ->setPropertyDefinition('id', $this->getMachineNameDefinition()->setRequired(TRUE))
      ->setPropertyDefinition('label', DataDefinition::create('string')->setRequired(TRUE))
      ->setPropertyDefinition('base path', DataDefinition::create('string')->setRequired(TRUE))
      ->setPropertyDefinition('file name', DataDefinition::create('string')->setRequired(TRUE))
      ->setPropertyDefinition('provider', DataDefinition::create('string')->setRequired(TRUE))
      ->setPropertyDefinition('fields', $this->getFieldsDefinition()->setRequired(TRUE))
      ->setPropertyDefinition('theme hook', DataDefinition::create('string')->setRequired(TRUE))
      ->setPropertyDefinition('description', DataDefinition::create('string'))
      ->setPropertyDefinition('use', DataDefinition::create('string'))
      ->setPropertyDefinition('tags', ListDataDefinition::create('string'))
      ->setPropertyDefinition('custom theme hook', DataDefinition::create('boolean'))
      ->setPropertyDefinition('template', DataDefinition::create('string'))
      ->setPropertyDefinition('libraries', DataDefinition::create('any'));
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
      ->addConstraint('Regex', sprintf(self::MACHINE_NAME, implode('|', $this->reserved)));
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
      ->setPropertyDefinition('label', DataDefinition::create('string')->setRequired(TRUE))
      ->setPropertyDefinition('type', $this->getMachineNameDefinition())
      ->setPropertyDefinition('description', DataDefinition::create('string'))
      ->setPropertyDefinition('preview', DataDefinition::create('any')));
  }

}
