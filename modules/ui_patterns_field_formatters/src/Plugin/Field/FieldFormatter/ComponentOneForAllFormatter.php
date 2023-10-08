<?php

namespace Drupal\ui_patterns_field_formatters\Plugin\Field\FieldFormatter;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\field_formatter\Plugin\Field\FieldFormatter\FieldWrapperBase;

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
