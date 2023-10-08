<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns\Plugin\UiPatterns\Source;

use Drupal\Core\Form\FormStateInterface;
use Drupal\ui_patterns\SourcePluginBase;

/**
 * Plugin implementation of the source_provider.
 *
 * @Source(
 *   id = "url",
 *   label = @Translation("Url"),
 *   description = @Translation("Input of a URL."),
 *   prop_types = {
 *     "url"
 *   }
 * )
 */
final class UrlWidget extends SourcePluginBase {

  /**
   *
   */
  public function getData(): mixed {
    return 'https://example.org';
  }

  /**
   *
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    return [
      '#type' => 'url',
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
