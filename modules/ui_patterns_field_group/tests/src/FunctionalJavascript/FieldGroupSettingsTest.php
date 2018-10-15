<?php

namespace Drupal\Tests\ui_patterns_field_group\FunctionalJavascript;

use Behat\Mink\Element\DocumentElement;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Test Field Group pattern settings.
 *
 * @group ui_patterns_field_group
 */
class FieldGroupSettingsTest extends WebDriverTestBase {

  /**
   * Disable schema validation when running tests.
   * @todo: Fix this by providing actual schema validation.
   *
   * @var bool
   */
  protected $strictConfigSchema = FALSE;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'field',
    'field_group',
    'field_ui',
    'text',
    'ui_patterns_field_group_test',
  ];

  /**
   * Make sure a warning message is displayed when using pattern formatter.
   */
  public function testWarningMessage() {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();

    $user = $this->drupalCreateUser([], null, true);
    $this->drupalLogin($user);

    // Visit Article's field group creation page.
    $this->drupalGet('/admin/structure/types/manage/article/display/add-group');

    // Add new Pattern field group.
    $page->selectFieldOption('Add a new group', 'Pattern');
    $assert_session->assertWaitOnAjaxRequest();

    // Select pattern and save.
    $page->fillField('Label', 'Metadata');
    $page->waitFor(10, function (DocumentElement $page) {
      return $page->hasContent('Machine name: group_metadata');
    });
    $page->pressButton('Save and continue');

    // Assert warning message.
    $assert_session->pageTextContains("Attention: you have to add fields to this field group and save the whole entity display before being able to to access the pattern display configuration.");
  }

}
