<?php

namespace Drupal\Tests\ui_patterns_field_group\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Test Field Group pattern rendering.
 *
 * @group ui_patterns_field_group
 */
class FieldGroupRenderTest extends WebDriverTestBase {

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
   * Test that pattern field group settings are correctly saved.
   */
  public function testFieldGroupRendering() {
    $assert_session = $this->assertSession();

    // Enable debug, rebuild the service container, and clear all caches.
    $parameters = $this->container->getParameter('twig.config');
    $parameters['debug'] = TRUE;
    $this->setContainerParameter('twig.config', $parameters);
    $this->rebuildContainer();
    $this->resetAll();

    $user = $this->drupalCreateUser([], null, true);
    $this->drupalLogin($user);

    $node = $this->drupalCreateNode([
      'title' => 'Test article',
      'field_text' => 'Test text field',
      'type' => 'article',
    ]);

    $this->drupalGet($node->toUrl());

    // Assert correct variant suggestions.
    $suggestions = [
      'pattern-metadata--variant-first--field-group--group-pattern-group--node--article--default.html.twig',
      'pattern-metadata--variant-first--field-group--group-pattern-group--node--default.html.twig',
      'pattern-metadata--variant-first--field-group--group-pattern-group--node--article.html.twig',
      'pattern-metadata--variant-first--field-group--group-pattern-group--node.html.twig',
      'pattern-metadata--variant-first--field-group--group-pattern-group.html.twig',
      'pattern-metadata--variant-first--field-group.html.twig',

      'pattern-metadata--field-group--group-pattern-group--node--article--default.html.twig',
      'pattern-metadata--field-group--group-pattern-group--node--default.html.twig',
      'pattern-metadata--field-group--group-pattern-group--node--article.html.twig',
      'pattern-metadata--field-group--group-pattern-group--node.html.twig',
      'pattern-metadata--field-group--group-pattern-group.html.twig',
      'pattern-metadata--field-group.html.twig',

      'pattern-metadata--variant-first.html.twig',
      'pattern-metadata.html.twig',
    ];
    foreach ($suggestions as $suggestion) {
      $assert_session->responseContains($suggestion);
    }

    // Test field content is rendered in field group pattern.
    $assert_session->pageTextContains('Field 1: Text Test text field');
  }

}
