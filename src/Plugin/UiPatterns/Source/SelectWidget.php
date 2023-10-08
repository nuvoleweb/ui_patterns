<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns\Plugin\UiPatterns\Source;

use Drupal\Core\Form\FormStateInterface;
use Drupal\ui_patterns\SourcePluginBase;

/**
 * Plugin implementation of the source.
 *
 * @Source(
 *   id = "select",
 *   label = @Translation("Select"),
 *   description = @Translation("A drop-down menu or scrolling selection box."),
 *   prop_types = {
 *     "enum"
 *   }
 * )
 */
final class SelectWidget extends SourcePluginBase {

  /**
   *
   */
  public function getData(): mixed {
    return 'Nice Site name';
  }

  /**
   *
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $options = [
      "foo",
      "poo",
    ];
    return [
      '#type' => 'select',
      '#title' => $this->propDefinition['title'],
      '#default_value' => $this->configuration['form_value'],
      "#options" => $options,
    ];
  }

  /**
   *
   */
  public function defaultConfiguration() {
    return [];
  }

}
