<?php

namespace Drupal\ui_patterns_props_widget\Plugin;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\sdc\Component\ComponentMetadata;
use Drupal\ui_patterns_props_widget\Definition\PropWidgetDefinition;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for UI Patterns Setting plugins.
 */
abstract class PropWidgetBase extends PluginBase implements ConfigurableInterface, PropWidgetInterface {

  /**
   * The component metadata object.
   *
   * @var \Drupal\sdc\Component\ComponentMetadata
   */
  private ComponentMetadata $componentMetadata;

  /**
   * The widget definition.
   *
   * @var \Drupal\ui_patterns_props_widget\Definition\PropWidgetDefinition
   */
  private PropWidgetDefinition $propWidgetDefinition;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition) {
    $configuration += $this->defaultConfiguration();
    $this->propWidgetDefinition = $configuration['prop_widget_definition'];
    $this->componentMetadata = $configuration['component_metadata'];
    unset($configuration['prop_widget_definition']);
    unset($configuration['component_metadata']);
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Return value if set otherwise take the default value.
   *
   * @param mixed $value
   *   The provided value.
   *
   * @return string
   *   The value for this setting
   */
  protected function getValue($value) {
    if ($value === NULL) {
      return $this->getPropWidgetDefinition()->getDefaultValue();
    }
    else {
      return $value ?? "";
    }
  }

  /**
   * Returns the widget definition.
   *
   * @return \Drupal\ui_patterns_settings\Definition\PropWidgetDefinition
   *   The widget definition.
   */
  protected function getPropWidgetDefinition() {
    return $this->propWidgetDefinition;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $plugin = new static($configuration, $plugin_id, $plugin_definition);

    /** @var \Drupal\Core\StringTranslation\TranslationInterface $translation */
    $translation = $container->get('string_translation');

    $plugin->setStringTranslation($translation);

    return $plugin;
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    $plugin_definition = $this->getPluginDefinition();
    return $plugin_definition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    $plugin_definition = $this->getPluginDefinition();
    return $plugin_definition['description'] ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = $configuration + $this->defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function preprocess($value, array $context) {
    $def = $this->getPropWidgetDefinition();
    $value = $this->propPreprocess($value, $context, $def);
    return $value;
  }

  /**
   * {@inheritdoc}
   */
  public function propPreprocess($value, array $context, PropWidgetDefinition $def) {
    return $value;
  }

  /**
   * Returns the bind form field.
   *
   * @param array $form
   *   The fieldset definition array for the widget form.
   * @param string $value
   *   The stored default value.
   * @param \Drupal\ui_patterns_props_widget\Definition\PropWidgetDefinition $def
   *   The widget definition.
   *
   * @return array
   *   The form.
   */
  protected function tokenForm(array $form, $value, PropWidgetDefinition $def) {
    $form[$def->getName() . "_token"] = [
      '#type' => 'textfield',
      '#title' => $this->t("Token for %label", ['%label' => $def->getLabel()]),
      '#default_value' => $this->getValue($value),
      '#attributes' => ['class' => ['js-ui-patterns-props-widget-show-token-link', 'js-ui-patterns-props-widget__token']],
      '#wrapper_attributes' => ['class' => ['js-ui-patterns-props-widget__token-wrapper']],
    ];
    return $form;
  }

  /**
   * Check required input fields in layout forms.
   *
   * @param array $element
   *   The element to validate.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   * @param array $form
   *   The form.
   */
  public static function validateLayout(array $element, FormStateInterface &$form_state, array &$form) {
    $parents = $element['#parents'];
    $value = $form_state->getValue($parents);
    $parents[count($parents) - 1] = $parents[count($parents) - 1] . '_token';
    $token_value = $form_state->getValue($parents);
    if (empty($value) && empty($token_value)) {
      // Check if a variant is selected and the value
      // is provided by the variant.
      $variant = $form_state->getValue([
        'layout_configuration',
        'pattern',
        'variant',
      ]);
      if (!empty($variant)) {
        $variant_def = $element['#pattern_definition']->getVariant($variant);
        $variant_ary = $variant_def->toArray();
        if (!empty($variant_ary['settings'][$element['#pattern_setting_definition']->getName()])) {
          return;
        }
      }

      $form_state->setError($element, t('@name field is required.', ['@name' => $element['#title']]));
    }
  }

  /**
   * Add validation and basics classes to the raw input field.
   *
   * @param array $input
   *   The input field.
   * @param \Drupal\ui_patterns_props_widget\Definition\PropWidgetDefinition $def
   *   The widget definition.
   * @param string $form_type
   *   The form type. Either layouts_display or display.
   */
  protected function handleInput(array &$input, PropWidgetDefinition $def, $form_type) {
    $input['#attributes']['class'][] = 'js-ui-patterns-props-widget__input';
    $input['#wrapper_attributes']['class'][] = 'js-ui-patterns-props-widget__input-wrapper';
    if ($def->getRequired()) {
      $input['#title'] .= ' *';
      if ($form_type === 'layouts_display') {
        $input['#prop_widget_definition'] = $this->propWidgetDefinition;
        $input['#component_metadata'] = $this->componentMetadata;
        $input['#element_validate'][] = [
          PropWidgetBase::class,
          'validateLayout',
        ];
      }
    }
  }

  /**
   * {@inheritdoc}
   *
   * Creates a generic configuration form for all widgets.
   * Individual widgets can add elements to this form by
   * overriding PatternSettingTypeBaseInterface::widgetForm().
   * Most plugins should not override this method unless they
   * need to alter the generic form elements.
   *
   * @see \Drupal\Core\Block\BlockBase::blockForm()
   */
  public function buildConfigurationForm(array $form, $value, $token_value, $form_type) {
    $def = $this->getPropWidgetDefinition();
    $form = $this->widgetForm($form, $value, $def, $form_type);
    $classes = 'js-ui-patterns-props-widget__wrapper';
    if ($def->getAllowToken()) {
      if (!empty($token_value)) {
        $classes .= ' js-ui-patterns-props-widget--token-has-value';
      }
      $form[$def->getName()]['#prefix'] = '<div class="' . $classes . '">';
    }
    if ($def->getAllowToken()) {
      $form = $this->tokenForm($form, $token_value, $def);
      $form[$def->getName() . '_token']['#suffix'] = '</div>';
    }

    return $form;
  }

}
