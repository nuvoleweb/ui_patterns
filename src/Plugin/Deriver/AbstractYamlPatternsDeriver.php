<?php

namespace Drupal\ui_patterns\Plugin\Deriver;

use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Site\Settings;
use Drupal\Core\TypedData\TypedDataManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AbstractYamlPatternsDeriver.
 *
 * Derive pattern plugin definitions stored in YAML files.
 *
 * @package Drupal\ui_patterns\Deriver
 */
abstract class AbstractYamlPatternsDeriver extends AbstractPatternsDeriver implements YamlPatternsDeriverInterface {

  /**
   * File system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * Constructor.
   *
   * @param string $base_plugin_id
   *   The base plugin ID.
   * @param \Drupal\Core\TypedData\TypedDataManager $typed_data_manager
   *   Typed data manager service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   Messenger.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   File system service.
   */
  public function __construct($base_plugin_id, TypedDataManager $typed_data_manager, MessengerInterface $messenger, FileSystemInterface $file_system) {
    parent::__construct($base_plugin_id, $typed_data_manager, $messenger);

    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $base_plugin_id,
      $container->get('typed_data_manager'),
      $container->get('messenger'),
      $container->get('file_system')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function fileScanDirectory($directory) {
    if (!is_dir($directory)) {
      return [];
    }
    $options = ['nomask' => $this->getNoMask()];
    $extensions = $this->getFileExtensions();
    $extensions = array_map('preg_quote', $extensions);
    $extensions = implode('|', $extensions);
    $files = $this->fileSystem->scanDirectory($directory, "/{$extensions}$/", $options);
    // In different file systems order of files in a folder can be different
    // that can break tests. So let's sort them alphabetically manually.
    ksort($files);
    return $files;
  }

  /**
   * Returns a regular expression for directories to be excluded in a file scan.
   *
   * @return string
   *   Regular expression.
   */
  protected function getNoMask() {
    $ignore = Settings::get('file_scan_ignore_directories', []);
    // We add 'tests' directory to the ones found in settings.
    $ignore[] = 'tests';
    array_walk($ignore, function (&$value) {
      $value = preg_quote($value, '/');
    });
    return '/^' . implode('|', $ignore) . '$/';
  }

}
