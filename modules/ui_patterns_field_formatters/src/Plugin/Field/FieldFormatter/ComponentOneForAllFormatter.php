<?php

namespace Drupal\ui_patterns_field_formatters\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\Plugin\DataType\EntityReference;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\Element;
use Drupal\field_formatter\Plugin\Field\FieldFormatter\FieldWrapperBase;
use Drupal\text\TextProcessed;
use Drupal\ui_patterns\Form\PatternDisplayFormTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\TypedData\Plugin\DataType\Uri;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'component_all' formatter.
 *
 * Field types are altered in
 * ui_patterns_field_formatters_field_formatter_info_alter().
 *
 * @FieldFormatter(
 *   id = "component_all",
 *   label = @Translation("Component (one for all)"),
 *   field_types = {
 *     "string"
 *   },
 * )
 */
class ComponentOneForAllFormatter extends FieldWrapperBase implements ContainerFactoryPluginInterface {

}
