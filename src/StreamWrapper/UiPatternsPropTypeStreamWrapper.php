<?php

namespace Drupal\ui_patterns\StreamWrapper;

use Drupal\Core\StreamWrapper\LocalReadOnlyStream;

/**
 * Defines the read-only ui-patterns:// stream wrapper for theme files.
 */
class UiPatternsPropTypeStreamWrapper extends LocalReadOnlyStream {

  /**
   *
   */
  public function stream_open($uri, $mode, $options, &$opened_path) {

    $plugin_id = str_replace('ui-patterns://', '', $uri);
    $plugin = \Drupal::service('plugin.manager.ui_patterns_prop_type')->getDefinition($plugin_id);
    $stream = fopen('php://memory', 'r+');
    fwrite($stream, json_encode($plugin['schema']));
    rewind($stream);
    $this->handle = $stream;
    return $stream;
  }

  /**
   *
   */
  public function getDirectoryPath() {
    return NULL;
  }

  /**
   *
   */
  public function getName() {
    return 'ui_patterns';
  }

  /**
   *
   */
  public function getDescription() {
    return 'ui_patterns';
  }

  /**
   *
   */
  public function getExternalUrl() {
    return NULL;
  }

}
