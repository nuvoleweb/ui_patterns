<?php

namespace Drupal\Tests\ui_patterns_views\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\ui_patterns\Traits\TwigDebugTrait;

/**
 * Test Views pattern rendering.
 *
 * @group ui_patterns_views
 */
class UiPatternsViewsRenderTest extends WebDriverTestBase {

  use TwigDebugTrait;

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
   * @todo Fix this by providing actual schema validation.
   */
  protected $strictConfigSchema = FALSE;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'field',
    'ui_patterns_views_test',
  ];

  /**
   * Test that pattern field group settings are correctly saved.
   */
  public function testUiPatternsViewsRendering() {
    $assert_session = $this->assertSession();

    $this->enableTwigDebugMode();

    $user = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($user);

    $this->drupalCreateNode([
      'title' => 'Test article',
      'type' => 'article',
    ]);

    $this->drupalGet('/articles');

    // Assert correct variant suggestions.
    $suggestions = [
      'pattern-teaser--variant-default--views-row--articles--page-1.html.twig',
      'pattern-teaser--variant-default--views-row--articles.html.twig',
      'pattern-teaser--variant-default--views-row.html.twig',

      'pattern-teaser--views-row--articles--page-1.html.twig',
      'pattern-teaser--views-row--articles.html.twig',
      'pattern-teaser--views-row.html.twig',

      'pattern-teaser--variant-default.html.twig',
      'pattern-teaser.html.twig',
    ];
    foreach ($suggestions as $suggestion) {
      $assert_session->responseContains($suggestion);
    }

    // Test field content is rendered in field group pattern.
    $assert_session->elementContains('css', 'h3', 'Test article');
  }

}
