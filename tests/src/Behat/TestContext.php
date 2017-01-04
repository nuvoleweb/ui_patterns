<?php

namespace NuvoleWeb\Drupal\Tests\Behat;

use NuvoleWeb\Drupal\DrupalExtension\Context\RawDrupalContext;
use function bovigo\assert\predicate\isNotEmpty;
use function bovigo\assert\assert;

/**
 * Class TestContext.
 *
 * @package NuvoleWeb\Drupal\Tests\Behat
 */
class TestContext extends RawDrupalContext {

  /**
   * Assert service container.
   *
   * @Given I can access the service container
   */
  public function assertServiceContainer() {
    assert($this->getContainer(), isNotEmpty());
  }

  /**
   * Assert service.
   *
   * @Then the service container can load the :name service
   */
  public function assertService($name) {
    $this->getContainer()->get($name);
  }

}
