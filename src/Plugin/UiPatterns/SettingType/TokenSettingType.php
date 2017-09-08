<?php

namespace Drupal\ui_patterns\Plugin\UIPatterns\SettingType;

use Drupal\Core\Entity\ContentEntityType;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\token\Token;
use Drupal\ui_patterns\Plugin\PatternSettingTypeBase;

/**
 * Entity value setting type.
 *
 * @UiPatternsSettingType(
 *   id = "token",
 *   label = @Translation("Token")
 * )
 */
class TokenSettingType extends PatternSettingTypeBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, $value) {
    $def = $this->getPatternSettingDefinition();
    $value = $this->getValue($value);
    $description = $this->getDescription() != NULL ? $this->getDescription() : $this->t('"Field name of entity property. (E.g: field_title, title)"');

    $content_entity_types = [];
    $entity_type_definations = \Drupal::entityTypeManager()->getDefinitions();
    /* @var $definition EntityTypeInterface */
    foreach ($entity_type_definations as $definition) {
      if ($definition instanceof ContentEntityType) {
        $content_entity_types[] = $definition->id();
      }
    }

    $form[$def->getName()] = array(
      '#type' => 'container',
    );

    $form[$def->getName()]['input'] = array(
      '#type' => 'textfield',
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $this->getValue($value),
      '#required' => $def->getRequired(),
    );

    $form[$def->getName()]['token'] = array(
      '#theme' => 'token_tree_link',
      "#description" => $description,
      '#token_types' => $content_entity_types,
      '#show_restricted' => TRUE,
      '#default_value' => $value,
      '#weight' => 90,
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function preprocess($value, $context) {
    /** @var \Drupal\Core\Entity\Entity $entity */
    $entity = isset($context['entity']) ? $context['entity'] : null;
    $return_value = '';
    if (!empty($value) && $entity !== null) {

      if (is_array($value) && !empty($value['input'])) {
        $value = $value['input'];
      }
      $token_service = \Drupal::token();
      $return_value = $token_service->replace($value,array($entity->getEntityTypeId() => $entity));
    }
    return $return_value;
  }

}
