<?php

namespace Drupal\ui_patterns;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\ui_patterns\Definition\PatternDefinition;

/**
 * Provides the default ui patterns manager.
 *
 * @method \Drupal\ui_patterns\Definition\PatternDefinition getDefinition($plugin_id, $exception_on_invalid = TRUE)
 */
class UiPatternsManager extends DefaultPluginManager implements UiPatternsManagerInterface {

  use StringTranslationTrait;

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * An array of pattern theme hooks for fast lookup on not cached pages.
   *
   * @var array
   */
  protected $patternHooks = [];

  /**
   * UiPatternsManager constructor.
   */
  public function __construct(
    \Traversable $namespaces,
    CacheBackendInterface $cache_backend,
    ModuleHandlerInterface $module_handler,
    ThemeHandlerInterface $theme_handler
  ) {
    parent::__construct('Plugin/UiPatterns/Pattern', $namespaces, $module_handler, 'Drupal\ui_patterns\Plugin\PatternInterface', 'Drupal\ui_patterns\Annotation\UiPattern');
    $this->setCacheBackend($cache_backend, 'ui_patterns', ['ui_patterns']);
    $this->alterInfo('ui_patterns_info');
    $this->themeHandler = $theme_handler;
  }

  /**
   * {@inheritdoc}
   */
  public function processDefinition(&$definition, $plugin_id) {
    parent::processDefinition($definition, $plugin_id);

    if (is_array($definition)) {
      $definition = new PatternDefinition($definition);
    }

    // Add default category.
    if ($definition instanceof PatternDefinition && empty($definition->getCategory())) {
      $definition->setCategory($this->t('Other'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCategories() {
    // Fetch all categories from definitions and remove duplicates.
    $categories = \array_unique(\array_values(\array_map(static function (PatternDefinition $definition) {
      return $definition->getCategory();
    }, $this->getDefinitions())));
    \natcasesort($categories);
    // @phpstan-ignore-next-line
    return $categories;
  }

  /**
   * {@inheritdoc}
   *
   * @phpstan-ignore-next-line
   */
  public function getSortedDefinitions(?array $definitions = NULL): array {
    $definitions = $definitions ?? $this->getDefinitions();

    \uasort($definitions, static function (PatternDefinition $item1, PatternDefinition $item2) {
      // Sort by category.
      $category1 = $item1->getCategory();
      if ($category1 instanceof TranslatableMarkup) {
        $category1 = $category1->render();
      }
      $category2 = $item2->getCategory();
      if ($category2 instanceof TranslatableMarkup) {
        $category2 = $category2->render();
      }
      if ($category1 != $category2) {
        return \strnatcasecmp($category1, $category2);
      }

      // Sort by weight.
      $weight = $item1->getWeight() <=> $item2->getWeight();
      if ($weight != 0) {
        return $weight;
      }

      // Sort by label ignoring parenthesis.
      $label1 = $item1->getLabel();
      if ($label1 instanceof TranslatableMarkup) {
        $label1 = $label1->render();
      }
      $label2 = $item2->getLabel();
      if ($label2 instanceof TranslatableMarkup) {
        $label2 = $label2->render();
      }
      // Ignore parenthesis.
      $label1 = \str_replace(['(', ')'], '', $label1);
      $label2 = \str_replace(['(', ')'], '', $label2);
      if ($label1 != $label2) {
        return \strnatcasecmp($label1, $label2);
      }

      // Sort by plugin ID.
      // In case the plugin ID starts with an underscore.
      $id1 = \str_replace('_', '', $item1->id());
      $id2 = \str_replace('_', '', $item2->id());
      return \strnatcasecmp($id1, $id2);
    });

    return $definitions;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedDefinitions(?array $definitions = NULL): array {
    $definitions = $this->getSortedDefinitions($definitions ?? $this->getDefinitions());
    $grouped_definitions = [];
    foreach ($definitions as $id => $definition) {
      $grouped_definitions[(string) $definition->getCategory()][$id] = $definition;
    }
    return $grouped_definitions;
  }

  /**
   * Return pattern definitions.
   *
   * @return \Drupal\ui_patterns\Definition\PatternDefinition[]
   *   Pattern definitions.
   */
  public function getDefinitions() {
    $definitions = $this->getCachedDefinitions();
    if (!isset($definitions)) {
      // Remove derivative id from pattern definitions keys.
      // @todo make sure validation takes care of ensuring ids are unique.
      $definitions = [];
      foreach ($this->findDefinitions() as $id => $definition) {
        $definitions[$definition['id']] = $definition;
        unset($definitions[$id]);
      }
      $this->setCachedDefinitions($definitions);
    }
    return $definitions;
  }

  /**
   * {@inheritdoc}
   */
  protected function providerExists($provider) {
    return $this->moduleHandler->moduleExists($provider) || $this->themeHandler->themeExists($provider);
  }

  /**
   * {@inheritdoc}
   */
  public function getPatterns(): array {
    $patterns = [];
    foreach ($this->getDefinitions() as $definition) {
      $patterns[] = $this->getFactory()->createInstance($definition->id());
    }
    return $patterns;
  }

  /**
   * {@inheritdoc}
   */
  public function getPatternsOptions(): array {
    $options = [];
    $grouped_definitions = $this->getGroupedDefinitions();
    foreach ($grouped_definitions as $group_name => $group_definitions) {
      foreach ($group_definitions as $definition) {
        $options[$group_name][$definition->id()] = $definition->getLabel();
      }
    }

    // If there is only one category, do not put in optgroup.
    if (count(array_keys($options)) == 1) {
      $options = array_shift($options);
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function isPatternHook(string $hook): bool {
    // Improve performance on not cached pages.
    if (empty($this->patternHooks)) {
      foreach ($this->getDefinitions() as $definition) {
        $this->patternHooks[$definition->getThemeHook()] = $definition->getThemeHook();
      }
    }
    return !empty($this->patternHooks[$hook]);
  }

}
