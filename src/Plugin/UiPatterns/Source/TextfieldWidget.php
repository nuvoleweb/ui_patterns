<?php declare(strict_types = 1);

namespace Drupal\ui_patterns\Plugin\UiPatterns\Source;

use Drupal\Core\Form\FormStateInterface;
use Drupal\ui_patterns\SourcePluginBase;

/**
 * Plugin implementation of the source_provider.
 *
 * @Source(
 *   id = "textfield",
 *   label = @Translation("Textfield"),
 *   description = @Translation("Foo description."),
 *   prop_types = {
 *     "string"
 *   }
 * )
 */
final class TextfieldWidget extends SourcePluginBase {

  public function getData(): mixed {
    return 'Nice Site name';
  }

  public function buildConfigurationForm(
    array $form,
    FormStateInterface $form_state
  ) {
    return parent::buildConfigurationForm(
      $form,
      $form_state
    );
    $form[$this->propId] = ['#type' => 'textfield', '#title' => $this->propDefinition['title']];
  }

  public function defaultConfiguration() {
    return [];
  }
}
