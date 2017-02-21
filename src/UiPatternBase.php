<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\PluginBase;

/**
 * Class UiPatternBase.
 *
 * @package Drupal\ui_patterns
 */
abstract class UiPatternBase extends PluginBase implements UiPatternInterface {

  /**
   * {@inheritdoc}
   */
  public function getFields() {
    return $this->getPluginDefinition()['fields'];
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraries() {
    return $this->getPluginDefinition()['libraries'];
  }

}
