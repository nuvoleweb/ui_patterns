<?php

namespace Drupal\ui_patterns_config\Loader;

/**
 * Class UiPatternsConfigLoader.
 *
 * @package Drupal\ui_patterns_config\Loader
 */
class UiPatternsConfigLoader extends \Twig_Loader_Filesystem {

  /**
   * {@inheritdoc}
   */
  public function __construct($paths = array()) {
    parent::__construct($paths);

    // @todo: See if this has to be changed.
    $name = 'ui_patterns_config';
    $path = drupal_realpath('public://ui_patterns_config');

    $this->addPath($path, $name);
  }

  /**
   * Adds a path where templates are stored.
   *
   * @param string $path
   *   A path where to look for templates.
   * @param string $namespace
   *   (optional) A path name.
   */
  public function addPath($path, $namespace = self::MAIN_NAMESPACE) {
    // Invalidate the cache.
    $this->cache = array();
    $this->paths[$namespace][] = rtrim($path, '/\\');
  }

}
