<?php

namespace Drupal\ui_patterns_config\Plugin\UiPatterns\Pattern;

use Drupal\ui_patterns\UiPatternBase;
use Drupal\ui_patterns\UiPatternInterface;
use Drupal\ui_patterns\UiPatternsManager;

/**
 * The UI Pattern Config plugin.
 *
 * @UiPattern(
 *   id = "ui_patterns_config",
 *   label = @Translation("UI Pattern Config"),
 *   description = @Translation("TODO"),
 *   deriver = "Drupal\ui_patterns_config\Plugin\Derivative\UiPatterns\Pattern\UiPatternConfig"
 * )
 */
class UiPatternConfig extends UiPatternBase implements UiPatternInterface {

  public function definition() {
    $definition = parent::definition();

    return $definition;
  }

  /**
   * Process 'use' definition property.
   *
   * @param array $definition
   *    Pattern definition array.
   *
   * @return array
   *    Processed hook definition portion.
   *
   * @throws \Twig_Error_Loader
   *    Throws exception if template is not found.
   *
   * @see UiPatternsManager::hookTheme()
   */
  protected function processUseProperty(array $definition) {
    $name = 'ui_patterns_config';
    $path = drupal_realpath('public://ui_patterns_config');
    $this->twigLoader->addPath($path, $name);

    $return = [];
    if (!empty($definition['use'])) {
      $template = $definition['use'];
      $parts = explode(DIRECTORY_SEPARATOR, $template);
      $name = array_pop($parts);
      $name = str_replace(UiPatternsManager::TWIG_EXTENSION, '', $name);

      $path = $this->twigLoader->getSourceContext($template)->getPath();
      $path = str_replace($this->root . DIRECTORY_SEPARATOR, '', $path);
      $path = str_replace(DIRECTORY_SEPARATOR . $name . UiPatternsManager::TWIG_EXTENSION, '', $path);

      $return = [
        'path' => $path,
        'template' => $name,
      ];
    }

    return $return;
  }

}
