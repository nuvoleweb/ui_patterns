<?php

namespace Drupal\ui_patterns_library\Plugin\UiPatterns\Pattern;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\TypedData\TypedDataManager;
use Drupal\ui_patterns\Definition\PatternDefinition;
use Drupal\ui_patterns\UiPatternBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The UI Pattern plugin.
 *
 * ID is set to "yaml" for backward compatibility reasons.
 *
 * @UiPattern(
 *   id = "yaml",
 *   label = @Translation("Library Pattern"),
 *   description = @Translation("Pattern defined using a YAML file."),
 *   deriver = "\Drupal\ui_patterns_library\Plugin\Deriver\LibraryDeriver"
 * )
 */
class LibraryPattern extends UiPatternBase {

  /**
   * Theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * UiPatternsManager constructor.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $root, TypedDataManager $typed_data_manager, ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $root, $typed_data_manager, $module_handler);
    $this->themeHandler = $theme_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('app.root'),
      $container->get('typed_data_manager'),
      $container->get('module_handler'),
      $container->get('theme_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getThemeImplementation() {
    $item = parent::getThemeImplementation();
    $definition = $this->getPluginDefinition();
    $item[$definition['theme hook']] += $this->processCustomThemeHookProperty($definition);
    $item[$definition['theme hook']] += $this->processTemplateProperty($definition);
    return $item;
  }

  /**
   * Process 'custom hook theme' definition property.
   *
   * @param \Drupal\ui_patterns\Definition\PatternDefinition $definition
   *    Pattern definition array.
   *
   * @return array
   *    Processed hook definition portion.
   */
  protected function processCustomThemeHookProperty(PatternDefinition $definition) {
    /** @var \Drupal\Core\Extension\Extension $module */
    $return = [];
    if (!$definition->getCustomThemeHook() && $this->moduleHandler->moduleExists($definition->getProvider())) {
      $module = $this->moduleHandler->getModule($definition->getProvider());
      $return['path'] = $module->getPath() . '/templates';
    }
    return $return;
  }

  /**
   * Process 'template' definition property.
   *
   * @param \Drupal\ui_patterns\Definition\PatternDefinition $definition
   *    Pattern definition array.
   *
   * @return array
   *    Processed hook definition portion.
   */
  protected function processTemplateProperty(PatternDefinition $definition) {
    $return = [];

    if ($definition->hasTemplate()) {
      $return = ['template' => $definition->getTemplate()];
    }
    return $return;
  }

}
