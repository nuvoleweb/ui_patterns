<?php

namespace Drupal\ui_patterns_views\Plugin\views\row;

use Drupal\ui_patterns\Form\PatternDisplayFormTrait;
use Drupal\views\Plugin\views\row\Fields;

/**
 * Pattern Views row plugin.
 *
 * @ingroup views_row_plugins
 *
 * @ViewsRow(
 *   id = "component",
 *   title = @Translation("Component"),
 *   help = @Translation("Displays fields using an UI component."),
 *   theme = "component_views_row",
 *   display_types = {"normal"}
 * )
 */
class Component extends Fields {

  use PatternDisplayFormTrait;

}
