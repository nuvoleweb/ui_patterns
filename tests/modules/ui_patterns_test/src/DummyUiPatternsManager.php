<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns_test;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\ui_patterns\UiPatternsManager;

/**
 * Plugin manager used for tests.
 *
 * @phpstan-ignore-next-line
 */
class DummyUiPatternsManager extends UiPatternsManager {

  /**
   * The list of patterns.
   *
   * @var array
   */
  protected array $patterns = [];

  /**
   * {@inheritdoc}
   *
   * @phpstan-ignore-next-line
   */
  public function __construct(
    \Traversable $namespaces,
    CacheBackendInterface $cache_backend,
    ModuleHandlerInterface $module_handler,
    ThemeHandlerInterface $theme_handler,
    TranslationInterface $translation
  ) {
    $this->stringTranslation = $translation;
    parent::__construct($namespaces, $cache_backend, $module_handler, $theme_handler);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitions(): array {
    $definitions = $this->patterns;
    foreach ($definitions as $plugin_id => &$definition) {
      $this->processDefinition($definition, $plugin_id);
    }
    return $definitions;
  }

  /**
   * Getter.
   *
   * getPatterns is already a method in the real plugin manager.
   *
   * @return array
   *   Property value.
   */
  public function getDummyPatterns(): array {
    return $this->patterns;
  }

  /**
   * Setter.
   *
   * @param array $patterns
   *   Property value.
   *
   * @return $this
   */
  public function setPatterns(array $patterns) {
    $this->patterns = $patterns;
    return $this;
  }

}
