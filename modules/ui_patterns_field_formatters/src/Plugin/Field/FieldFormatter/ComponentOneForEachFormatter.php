<?php

namespace Drupal\ui_patterns_field_formatters\Plugin\Field\FieldFormatter;

/**
 * Plugin implementation of the 'component_each' formatter.
 *
 * Field types are altered in
 * ui_patterns_field_formatters_field_formatter_info_alter().
 *
 * @FieldFormatter(
 *   id = "component_each",
 *   label = @Translation("Component (one for each)"),
 *   field_types = {
 *     "string"
 *   },
 * )
 */
class ComponentOneForEachFormatter extends ComponentOneForAllFormatter {

}
