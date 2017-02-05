<?php

namespace Drupal\ui_patterns\Tests\Behat;

use NuvoleWeb\Drupal\DrupalExtension\Context\RawDrupalContext;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Drupal\Core\Config\FileStorage;
use Drupal\Core\Config\ConfigImporter;
use Drupal\Core\Config\StorageComparer;

/**
 * Class ConfigContext.
 *
 * @package Drupal\ui_patterns\Tests\Behat
 */
class ConfigContext extends RawDrupalContext {

  /**
   * Configuration storage directory.
   *
   * @var string
   */
  protected $directory = '';

  /**
   * Configuration storage directory.
   *
   * @var \Symfony\Component\Filesystem\Filesystem
   */
  protected $fs;

  /**
   * ConfigContext constructor.
   *
   * @param string $directory
   *    Full path to configuration temporary storage directory.
   */
  public function __construct($directory = '') {
    $this->fs = new Filesystem();
    $this->directory = $directory;
    if (empty($this->directory)) {
      $this->directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'drupal-behat' . DIRECTORY_SEPARATOR . 'config';
    }
  }

  /**
   * Backup site configuration.
   *
   * @BeforeScenario @preserveConfiguration
   */
  public function backupConfiguration() {
    $this->fs->remove($this->directory);
    $this->fs->mkdir($this->directory);

    /** @var \Drupal\Core\Config\CachedStorage $source_storage */
    $source_storage = \Drupal::service('config.storage');
    $destination_storage = new FileStorage($this->directory);

    // Export configuration.
    foreach ($source_storage->listAll() as $name) {
      $destination_storage->write($name, $source_storage->read($name));
    }

    // Export configuration collections.
    foreach (\Drupal::service('config.storage')->getAllCollectionNames() as $collection) {
      $source_storage = $source_storage->createCollection($collection);
      $destination_storage = $destination_storage->createCollection($collection);
      foreach ($source_storage->listAll() as $name) {
        $destination_storage->write($name, $source_storage->read($name));
      }
    }
  }

  /**
   * Restore site configuration.
   *
   * @AfterScenario @preserveConfiguration
   */
  public function restoreConfiguration() {
    if (!$this->fs->exists($this->directory)) {
      throw new IOException("Source configuration directory '{$this->directory}' does not exists.");
    }

    $active_storage = \Drupal::service('config.storage');
    $source_storage = new FileStorage($this->directory);
    $config_manager = \Drupal::service('config.manager');
    $storage_comparer = new StorageComparer($source_storage, $active_storage, $config_manager);

    $config_importer = new ConfigImporter(
      $storage_comparer,
      \Drupal::service('event_dispatcher'),
      \Drupal::service('config.manager'),
      \Drupal::lock(),
      \Drupal::service('config.typed'),
      \Drupal::moduleHandler(),
      \Drupal::service('module_installer'),
      \Drupal::service('theme_handler'),
      \Drupal::service('string_translation')
    );
    $config_importer->import();
  }

}
