<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns\Plugin\UiPatterns\Source;

use Drupal\Core\Form\FormStateInterface;
use Drupal\ui_patterns\SourcePluginBase;

/**
 * Plugin implementation of the source_provider.
 *
 * @Source(
 *   id = "menu",
 *   label = @Translation("Menu"),
 *   description = @Translation("Foo description."),
 *   prop_types = {
 *     "links"
 *   }
 * )
 */
final class MenuSource extends SourcePluginBase {

  /**
   *
   */
  public function getData(): mixed {
    return [];
  }

  /**
   *
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $value = $value ?? [];
    $form["menu"] = [
      '#type' => 'select',
      '#title' => 'Menu',
      '#options' => $this->getMenuList(),
      '#default_value' => \array_key_exists("menu", $value) ? $value["menu"] : "",
    ];
    $options = range(0, $this->menuLinkTree->maxDepth());
    unset($options[0]);
    $form['level'] = [
      '#type' => 'select',
      '#title' => $this->t('Initial visibility level'),
      '#default_value' => \array_key_exists("level", $value) ? $value["level"] : 1,
      '#options' => $options,
      '#required' => TRUE,
    ];
    $options[0] = $this->t('Unlimited');
    $form['depth'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of levels to display'),
      '#default_value' => \array_key_exists("depth", $value) ? $value["depth"] : 0,
      '#options' => $options,
      '#description' => $this->t('This maximum number includes the initial level and the final display is dependant of the pattern template.'),
      '#required' => TRUE,
    ];

    return $form;

  }

  /**
   *
   */
  public function defaultConfiguration() {
    return [];
  }

}
