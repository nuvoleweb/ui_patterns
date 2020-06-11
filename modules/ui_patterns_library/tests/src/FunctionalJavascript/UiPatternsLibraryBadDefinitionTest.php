<?php

namespace Drupal\Tests\ui_patterns_library\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Test invalid definition error messages.
 *
 * @group ui_patterns_library
 */
class UiPatternsLibraryBadDefinitionTest extends WebDriverTestBase {

  /**
   * Default theme.
   *
   * @var string
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'ui_patterns',
    'ui_patterns_library',
    'ui_patterns_library_bad_definition_test',
  ];

  /**
   * Test error messages for invalid pattern definitions.
   */
  public function testErrorMessages() {
    $session = $this->assertSession();

    $user = $this->drupalCreateUser(['access patterns page']);
    $this->drupalLogin($user);

    drupal_flush_all_caches();
    $this->drupalGet('/patterns');

    $session->pageTextContains("Pattern 'bad_definition' is skipped because of the following validation error(s):");
    $session->pageTextContains('Validation error on "bad_definition.label": This value should not be null.');
  }

}
