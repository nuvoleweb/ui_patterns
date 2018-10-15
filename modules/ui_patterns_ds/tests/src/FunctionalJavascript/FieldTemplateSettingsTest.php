<?php

namespace Drupal\Tests\ui_patterns_ds\FunctionalJavascript;

use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Test Display Suite field template settings UI.
 *
 * @group ui_patterns_ds
 */
class FieldTemplateSettingsTest extends WebDriverTestBase {

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
    'field_ui',
    'text',
    'ui_patterns',
    'ui_patterns_ds',
    'ui_patterns_library',
    'ui_patterns_ds_test',
    'dblog',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Enable Display Suite field templates.
    \Drupal::configFactory()
      ->getEditable('ds.settings')
      ->set('field_template', TRUE)
      ->save();

    // Create test content type and assign a Display Suite layout to it
    // so that Display Suite's field templates can be assigned to its fields.
    $this->drupalCreateContentType([
      'type' => 'article',
      'name' => 'Article',
    ]);
  }

  /**
   * Tests field template settings.
   */
  public function testFieldTemplateSettings() {
    $session = $this->getSession();
    $page = $session->getPage();
    $assert_session = $this->assertSession();

    $user = $this->drupalCreateUser([], null, true);
    $this->drupalLogin($user);

    // Visit Article's default display settings page.
    $this->drupalGet('/admin/structure/types/manage/article/display');

    // Select a Display suite layout so that we can access field template
    // settings on one of its fields.
    $page->selectFieldOption('layout', 'One column layout');
    $assert_session->assertWaitOnAjaxRequest();

    // Save view mode setting page.
    $page->pressButton('Save');

    // Click on Body field display settings.
    $page->pressButton('body_plugin_settings_edit');
    $assert_session->assertWaitOnAjaxRequest();

    // Select "Pattern" field template.
    $page->selectFieldOption('Choose a Field Template', 'Pattern');
    $assert_session->assertWaitOnAjaxRequest();

    // Choose test pattern.
    $page->selectFieldOption('fields[body][settings_edit_form][third_party_settings][ds][ft][settings][pattern]', 'Button');
    $assert_session->assertWaitOnAjaxRequest();

    // Choose test variant.
    $page->selectFieldOption('Variant', 'Primary');
    $assert_session->assertWaitOnAjaxRequest();

    // Submit field settings.
    $page->pressButton('Update');
    $assert_session->assertWaitOnAjaxRequest();

    // Save view mode setting page.
    $page->pressButton('Save');

    // Get default view mode for Article node bundle.
    $display = EntityViewDisplay::load("node.article.default");

    // Assert existence of third party settings.
    $third_party_settings = $display->getComponent('body')['third_party_settings'];
    $this->assertNotEmpty($third_party_settings['ds']['ft'], "Field template settings not found.");

    // Assert settings value.
    $settings = $third_party_settings['ds']['ft'];
    $this->assertEquals($settings['id'], 'pattern');
    $this->assertEquals($settings['settings']['pattern'], 'button');
    $this->assertEquals($settings['settings']['pattern_variant'], 'primary');
  }

}
