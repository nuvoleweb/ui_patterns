<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use Drupal\KernelTests\KernelTestBase;
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
        $definitions["fixture:$id"] = $definition;
        $definitions["fixture:$id"]['id'] = $id;
      }
    }
    return $definitions;
  }

  /**
   * Return plugin manager using test fixture definitions.
   *
   * @return \Drupal\ui_patterns\UiPatternsManager
   *    Plugin manager object.
   */
  protected function getFixturePluginManager() {
    /** @var \Drupal\ui_patterns\UiPatternsManager $manager */
    $manager = \Drupal::service('plugin.manager.ui_patterns');
    $manager_mock = $this->getMockBuilder(UiPatternsManager::class)
      ->disableOriginalConstructor()
      ->setMethods(['findDefinitions'])
      ->setProxyTarget($manager)
      ->getMock();
    $manager_mock->method('findDefinitions')->willReturn($this->getFixtureDefinitions());

    /** @var \Drupal\ui_patterns\UiPatternsManager $manager_mock */
    return $manager_mock;
  }

}
