<?php

namespace Drupal\ui_patterns_token\Plugin\Filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * The UI Pattern Filter plugin.
 *
 * @Filter(
 *   id = "ui_patterns_filter",
 *   title = @Translation("UI Patterns"),
 *   description = @Translation("Provides UI Patterns."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class UiPatternsFilter extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    preg_match_all('/\[ui-pattern\|(.*)\]/', $text, $matches);

    if (empty($matches)) {
      return new FilterProcessResult($text);
    }

    // @todo: see if it is possible to inject this service properly.
    $uiPatternManager = \Drupal::service('plugin.manager.ui_patterns');
    $definitions = $uiPatternManager->getDefinitions();
    list($strings_to_replace, $parameters) = $matches;

    foreach ($strings_to_replace as $index => $match) {
      $pattern_parameters = explode('|', $parameters[$index]);
      $pattern = array_shift($pattern_parameters);

      if (!isset($definitions[$pattern])) {
        continue;
      }

      $fields = [];
      foreach (array_keys($definitions[$pattern]['fields']) as $idx => $field_name) {
        $fields[$field_name] = isset($pattern_parameters[$idx]) ? $pattern_parameters[$idx] : NULL;
      }

      $element = [
        '#type' => 'pattern',
        '#id' => $pattern,
        '#fields' => array_filter($fields),
      ];

      $new_text = \Drupal::service('renderer')->render($element);
      $text = str_replace($match, $new_text, $text);
    }

    return new FilterProcessResult($text);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $uiPatternManager = \Drupal::service('plugin.manager.ui_patterns');

    foreach ($uiPatternManager->getDefinitions() as $pattern) {
      $form[$pattern['id']] = [
        '#type' => 'checkbox',
        '#title' => $pattern['label'],
      ];
    }

    return $form;
  }

}
