<?php

namespace Drupal\ui_patterns_ds\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests ui patterns display suite config options.
 *
 * @group ui_patterns
 */
class ConfigOptionTest extends WebTestBase {

  /**
   * A user with permission to administer views.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $adminUser;

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = [
    'node',
    'user',
    'field_ui',
    'ds',
    'layout_plugin',
    'ui_patterns_ds',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    // Create a test user.
    $this->adminUser = $this->drupalCreateUser([
      'admin display suite',
      'administer node display',
    ]);
    $this->adminUser->addRole('administrator');
    $this->drupalLogin($this->adminUser);

    // Create a node type.
    $contentType = $this->drupalCreateContentType([
      'type' => 'page',
      'name' => 'Page',
      'revision' => TRUE,
    ]);
    node_add_body_field($contentType);
  }

  /**
   * Tests that we can configure the ui_patterns field formatter as default.
   */
  public function testDsFieldDefault() {
    // Go to the ds field settings page.
    $this->drupalGet('admin/structure/ds/settings');

    // Check that Patterns is available as an option.
    $this->assertOptionByText('edit-fs1-ft-default', 'Pattern');
    $this->assertOption('edit-fs1-ft-default', 'pattern');

    // Chose patterns from the select and save.
    $form = [
      'fs1[field_template]' => TRUE,
      'fs1[ft-default]' => 'pattern',
    ];

    $this->drupalPostForm(NULL, $form, t('Save configuration'));
    $this->assertText('The configuration options have been saved.');
    $this->assertOptionSelected('edit-fs1-ft-default', 'pattern');

    // Go to node display settings and check the default value.
    $this->drupalGet('admin/structure/types/manage/page/display');
    $this->assertText('Field template: pattern');
  }

}
