<?php

namespace Drupal\ui_patterns\Plugin\DataType;

use Drupal\Core\TypedData\ComplexDataDefinitionBase;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Class SourceFieldDefinition.
 *
 * @package Drupal\ui_patterns\Plugin\DataType
 */
class SourceFieldDefinition extends ComplexDataDefinitionBase {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions() {
    $this->propertyDefinitions['field_name'] = DataDefinition::create('string')
      ->setLabel('Field machine name')
      ->setRequired(TRUE);
    $this->propertyDefinitions['field_label'] = DataDefinition::create('string')
      ->setLabel('Field human readable label')
      ->setRequired(TRUE);
    $this->propertyDefinitions['plugin'] = DataDefinition::create('string')
      ->setLabel('Plugin ID')
      ->setRequired(TRUE);
    $this->propertyDefinitions['plugin_label'] = DataDefinition::create('string')
      ->setLabel('Plugin human readable label')
      ->setRequired(TRUE);
    return $this->propertyDefinitions;
  }

}
