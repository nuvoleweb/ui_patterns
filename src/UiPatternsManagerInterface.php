<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\CategorizingPluginManagerInterface;
use Drupal\Component\Plugin\PluginManagerInterface;

/**
 * Defines an interface for ui patterns plugin managers.
 */
interface UiPatternsManagerInterface extends PluginManagerInterface, CategorizingPluginManagerInterface {

  /**
   * {@inheritdoc}
   *
   * @return \Drupal\ui_patterns\Definition\PatternDefinition|null
   *   The plugin definition. NULL if not found.
   *
   * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
   */
  public function getDefinition($plugin_id, $exception_on_invalid = TRUE);

  /**
   * {@inheritdoc}
   *
   * @return \Drupal\ui_patterns\Definition\PatternDefinition[]
   *   The plugins definitions.
   */
  public function getDefinitions();

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\ui_patterns\Definition\PatternDefinition[]|null $definitions
   *   (optional) The plugin definitions to sort. If omitted, all plugin
   *   definitions are used.
   *
   * @return \Drupal\ui_patterns\Definition\PatternDefinition[]
   *   The sorted definitions.
   *
   * @phpstan-ignore-next-line
   */
  public function getSortedDefinitions(?array $definitions = NULL): array;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\ui_patterns\Definition\PatternDefinition[]|null $definitions
   *   (optional) The plugin definitions to group. If omitted, all plugin
   *   definitions are used.
   *
   * @return \Drupal\ui_patterns\Definition\PatternDefinition[][]
   *   The sorted definitions grouped by category.
   */
  public function getGroupedDefinitions(?array $definitions = NULL): array;

  /**
   * Get pattern objects.
   *
   * @return \Drupal\ui_patterns\Plugin\PatternInterface[]
   *   Pattern objects.
   */
  public function getPatterns(): array;

  /**
   * Get patterns as form API select options.
   *
   * @return array
   *   An array of options for the form API.
   */
  public function getPatternsOptions(): array;

  /**
   * Check if the theme hook if for a pattern.
   *
   * @param string $hook
   *   The theme hook.
   *
   * @return bool
   *   TRUE if related to a pattern. FALSE otherwise.
   */
  public function isPatternHook(string $hook): bool;

}
