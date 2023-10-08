<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns_views\Plugin\views\style;

use Drupal\ui_patterns\Form\UiPatternsFormBuilderTrait;
use Drupal\views\Plugin\views\style\StylePluginBase;

/**
 * Style plugin to render items in a pattern field.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *     id = "component",
 *     title = @Translation("Component"),
 *     help = @Translation("Displays views with UI components."),
 *     theme = "view--pattern",
 *     display_types = {"normal"}
 * )
 */
class Component extends StylePluginBase {
  use UiPatternsFormBuilderTrait;

}
