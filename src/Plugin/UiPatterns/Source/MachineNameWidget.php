<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns\Plugin\UiPatterns\Source;

use Drupal\Core\Form\FormStateInterface;
use Drupal\ui_patterns\SourcePluginBase;

/**
 * Plugin implementation of the source_provider.
 *
 * @Source(
 *   id = "machine_name",
 *   label = @Translation("Machine name"),
 *   description = @Translation("Validated to ensure that the name does not contain disallowed characters."),
 *   prop_types = {
 *     "machine_name"
 *   }
 * )
 */
final class MachineNameWidget extends SourcePluginBase {

  /**
   *
   */
  public function getData(): mixed {
    return 'abc_123';
  }

  /**
   *
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    return [
      '#type' => 'machine_name',
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
