<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\ui_patterns\UiPatterns;
use Symfony\Component\Finder\Finder;
use Drupal\Component\Serialization\Yaml;
use Drupal\ui_patterns\UiPatternsManager;

/**
 * Class AbstractUiPatternsTest.
 *
 * @package Drupal\Tests\ui_patterns\Kernel
 */
abstract class AbstractUiPatternsTest extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'ui_patterns',
    'ui_patterns_test',
  ];

  /**
   * Get fixture definitions.
   *
   * @return array
   *    List of definitions.
   */
  protected function getFixtureDefinitions() {
    $definitions = [];
    $finder = new Finder();
    $finder->name('/\.ui_patterns\.yml$/')->in([realpath(dirname(__FILE__) . '/../fixtures')]);
    foreach ($finder as $file) {
      $content = Yaml::decode($file->getContents());
      foreach ($content as $id => $definition) {
        $definitions["yaml:$id"] = $definition;
        $definitions["yaml:$id"]['id'] = $id;
        $definitions["yaml:$id"]['class'] = '\Drupal\ui_patterns\Plugin\UiPatterns\Pattern\YamlPattern';
      }
    }
    return $definitions;
  }

  /**
   * Return plugin manager using given definitions.
   *
   * @param array $definitions
   *    Array of plugin definitions.
   *
   * @return \Drupal\ui_patterns\UiPatternsManager
   *    Plugin manager object.
   */
  protected function getPluginManager(array $definitions) {
    $manager = UiPatterns::getManager();
    $manager_mock = $this->getMockBuilder(UiPatternsManager::class)
      ->disableOriginalConstructor()
      ->setMethods(['findDefinitions'])
      ->setProxyTarget($manager)
      ->getMock();
    $manager_mock->method('findDefinitions')->willReturn($definitions);

    /** @var \Drupal\ui_patterns\UiPatternsManager $manager_mock */
    return $manager_mock;
  }

}
