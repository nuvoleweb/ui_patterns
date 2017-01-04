<?php

namespace Drupal\ui_patterns\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\Core\Theme\ThemeManager;
use Drupal\ui_patterns\UiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PatternLibraryController.
 *
 * @package Drupal\ui_patterns\Controller
 */
class PatternLibraryController extends ControllerBase {

  /**
   * Patterns manager service.
   *
   * @var \Drupal\ui_patterns\UiPatternsManager
   */
  protected $patternsManager;

  /**
   * Theme manager service.
   *
   * @var \Drupal\Core\Theme\ThemeManager
   */
  protected $themeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(UiPatternsManager $ui_patterns_manager, ThemeManager $theme_manager) {
    $this->themeManager = $theme_manager;
    $this->patternsManager = $ui_patterns_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.ui_patterns'),
      $container->get('theme.manager')
    );
  }

  /**
   * Render pattern library page.
   *
   * @return array
   *   Return render array.
   */
  public function overview() {

    $definitions = $this->patternsManager->getDefinitions();
    foreach ($definitions as $name => $definition) {
      $render = [];
      try {
        $render = $this->themeManager->render($definition['theme hook'], $this->getVariables($name));
      }
      catch (\Twig_Error_Loader $e) {
        drupal_set_message($e->getRawMessage(), 'error');
      }
      $definitions[$name]['rendered'] = $render;
    }

    return [
      '#theme' => 'patterns_overview_page',
      '#patterns' => $definitions,
    ];
  }

  /**
   * Render pattern library page.
   *
   * @return array
   *   Return render array.
   */
  public function single($name) {

    $definition = $this->patternsManager->getDefinition($name);
    $definition['rendered'] = $this->themeManager->render($definition['theme hook'], $this->getVariables($name));

    return [
      '#theme' => 'patterns_single_page',
      '#pattern' => $definition,
    ];
  }

  /**
   * Title callback.
   *
   * @return string
   *   Pattern label.
   */
  public function title($name) {
    $definition = $this->patternsManager->getDefinition($name);
    return $definition['label'];
  }

  /**
   * Get variables for given pattern function.
   *
   * @param string $name
   *    Pattern name, i.e. its theme function.
   *
   * @return array
   *    Variables array.
   */
  protected function getVariables($name) {
    $variables = [];
    $definition = $this->patternsManager->getDefinition($name);
    foreach ($definition['fields'] as $name => $field) {
      // Some fields are used as twig array keys and don't need escaping.
      if (!isset($field['escape']) || $field['escape'] != FALSE) {
        // The examples are not user submitted and are safe markup.
        $field['example'] = self::getExampleMarkup($field['example']);
      }

      $variables[$name] = $field['example'];
    }

    if (isset($definition['extra']['attributes'])) {
      $variables['attributes'] = $definition['extra']['attributes'];
    }

    return $variables;
  }

  /**
   * Make safe markup out of the example strings.
   *
   * @param string|string[] $example
   *   The example, may be a string or an array.
   *
   * @return array|\Drupal\Component\Render\MarkupInterface|string
   *   The safe markup of the example
   */
  protected static function getExampleMarkup($example) {
    if (is_array($example)) {
      // Check to see if the example is a render array.
      if (array_key_exists('theme', $example) || array_key_exists('type', $example)) {
        $rendered = [];
        foreach ($example as $key => $value) {
          $rendered['#' . $key] = $value;
        }

        return $rendered;
      }

      // Recursively escape the string elements.
      return array_map([self::class, __METHOD__], $example);
    }

    return Markup::create($example);
  }

}
