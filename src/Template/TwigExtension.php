<?php

namespace Drupal\ui_patterns\Template;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Twig extension providing UI Patterns-specific functionalities.
 *
 * @package Drupal\ui_patterns\Template
 */
class TwigExtension extends AbstractExtension {

  use AttributesFilterTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'ui_patterns';
  }

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    return [
      new TwigFilter('add_class', [$this, 'addClass']),
      new TwigFilter('set_attribute', [$this, 'setAttribute']),
    ];
  }

}
