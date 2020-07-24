<?php

namespace Drupal\Tests\ui_patterns_field_group\FunctionalJavascript;

use Behat\Mink\Element\DocumentElement;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Test Field Group pattern settings.
 *
 * @group ui_patterns_field_group
 */
class UiPatternsFieldGroupSettingsTest extends WebDriverTestBase {

  /**
   * Default theme.
   *
   * @var string
   */
  protected $defaultTheme = 'stark';

  /**
   * Disable schema validation when running tests.
   *
   * @var bool
   *
   * @todo: Fix this by providing actual schema validation.
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

    $user = $this->drupalCreateUser([], NULL, TRUE);
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

  /**
   * Test that pattern field group settings are correctly saved.
   */
  public function testUiPatternsFieldGroupSettings() {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();

    $user = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($user);

    // Visit Article's default view mode page.
    $this->drupalGet('/admin/structure/types/manage/article/display');

    // Click on field group settings button.
    $page->pressButton('group_pattern_group_group_settings_edit');
    $assert_session->assertWaitOnAjaxRequest();

    // Choose variant.
    $page->selectFieldOption('Variant', 'Second');
    $page->selectFieldOption('Destination for Text', 'Field 2');
    $assert_session->assertWaitOnAjaxRequest();

    // Submit field group settings.
    $page->pressButton('Update');
    $assert_session->assertWaitOnAjaxRequest();

    // Save view mode setting page.
    $page->pressButton('Save');

    // Get default view mode for Article node bundle.
    $display = EntityViewDisplay::load("node.article.default");

    // Assert existence of third party settings.
    $settings = $display->getThirdPartySetting('field_group', 'group_pattern_group');

    // Assert settings value.
    $this->assertEquals($settings['format_type'], 'pattern_formatter');
    $this->assertEquals($settings['format_settings']['pattern'], 'metadata');
    $this->assertEquals($settings['format_settings']['pattern_variant'], 'second');

    // Assert mappings.
    $this->assertNotEmpty($settings['format_settings']['pattern_mapping'], "Pattern mapping is empty.");

    $mapping = $settings['format_settings']['pattern_mapping'];
    $this->assertArrayHasKey('fields:field_text', $mapping, 'Mapping not found.');
    $this->assertEquals($mapping['fields:field_text']['destination'], 'field_2', "Mapping not valid.");
  }

}
