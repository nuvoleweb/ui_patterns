<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns\Plugin\UiPatterns\Source;

use Drupal\Core\Form\FormStateInterface;
use Drupal\ui_patterns\SourcePluginBase;

/**
 * Plugin implementation of the source_provider.
 *
 * @Source(
 *   id = "number",
 *   label = @Translation("Number"),
 *   description = @Translation("Numeric input, with special numeric validation."),
 *   prop_types = {
 *     "number"
 *   }
 * )
 */
final class NumberWidget extends SourcePluginBase {

  /**
   *
   */
  public function getData(): mixed {
    return 42;
  }

  /**
   *
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    return [
      '#type' => 'number',
      '#title' => $this->propDefinition['title'],
      '#default_value' => $this->configuration['form_value'],
    ];
  }

  /**
   *
   */
  public function defaultConfiguration() {
    return [];
  }

}
