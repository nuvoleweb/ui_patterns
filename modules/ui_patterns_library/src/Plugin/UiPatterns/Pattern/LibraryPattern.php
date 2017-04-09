<?php

namespace Drupal\ui_patterns_library\Plugin\UiPatterns\Pattern;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\TypedData\TypedDataManager;
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
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * UiPatternsManager constructor.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $root, TypedDataManager $typed_data_manager, ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $root, $typed_data_manager);
    $this->moduleHandler = $module_handler;
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
    $item = [];
    $definition = $this->getPluginDefinition();

    foreach ($definition['fields'] as $field) {
      $item['variables'][$field['name']] = NULL;
    }
    $item['variables']['attributes'] = [];
    $item['variables']['context'] = [];
    $item['variables']['use'] = '';

    $item += $this->processUseProperty($definition);
    $item += $this->processCustomThemeHookProperty($definition);
    $item += $this->processTemplateProperty($definition);

    return [
      $definition['theme hook'] => $item,
    ];
  }

  /**
   * Process 'custom hook theme' definition property.
   *
   * @param array $definition
   *    Pattern definition array.
   *
   * @return array
   *    Processed hook definition portion.
   */
  protected function processCustomThemeHookProperty(array $definition) {
    /** @var \Drupal\Core\Extension\Extension $module */
    $return = [];
    if (!$definition['custom theme hook'] && $this->moduleHandler->moduleExists($definition['provider'])) {
      $module = $this->moduleHandler->getModule($definition['provider']);
      $return['path'] = $module->getPath() . '/templates';
    }
    return $return;
  }

  /**
   * Process 'template' definition property.
   *
   * @param array $definition
   *    Pattern definition array.
   *
   * @return array
   *    Processed hook definition portion.
   */
  protected function processTemplateProperty(array $definition) {
    $return = [];
    if (isset($definition['template'])) {
      $return = ['template' => $definition['template']];
    }
    return $return;
  }

  /**
   * Process 'use' definition property.
   *
   * @param array $definition
   *    Pattern definition array.
   *
   * @return array
   *    Processed hook definition portion.
   */
  protected function processUseProperty(array $definition) {
    $return = [];
    if (!empty($definition['use'])) {
      $return = [
        'path' => $this->moduleHandler->getModule('ui_patterns')->getPath() . '/templates',
        'template' => 'patterns-use-wrapper',
      ];
    }
    return $return;
  }

}
