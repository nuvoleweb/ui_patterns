<?php

declare(strict_types = 1);

namespace Drupal\Tests\ui_patterns\Unit;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Tests\UnitTestCase;
use Drupal\ui_patterns\Definition\PatternDefinition;
use Drupal\ui_patterns\UiPatternsManager;
use Drupal\ui_patterns_test\DummyUiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Test the ui patterns plugin manager.
 *
 * @group ui_patterns
 *
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternsManager
 */
class UiPatternsManagerTest extends UnitTestCase {

  /**
   * The container.
   *
   * @var \Symfony\Component\DependencyInjection\TaggedContainerInterface
   */
  protected $container;

  /**
   * The string translation service.
   *
   * @var \Drupal\Core\StringTranslation\TranslationInterface
   */
  protected TranslationInterface $stringTranslation;

  /**
   * The ui patterns manager.
   *
   * @var \Drupal\ui_patterns_test\DummyUiPatternsManager
   */
  protected DummyUiPatternsManager $uiPatternsManager;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->container = new ContainerBuilder();
    $this->container->set('string_translation', $this->getStringTranslationStub());

    // Set up for this class.
    $namespaces = $this->createMock(\Traversable::class);

    /** @var \Drupal\Core\Extension\ModuleHandlerInterface|\PHPUnit\Framework\MockObject\MockObject $moduleHandler */
    $moduleHandler = $this->createMock(ModuleHandlerInterface::class);
    $moduleHandler->expects($this->any())
      ->method('getModuleDirectories')
      ->willReturn([]);

    /** @var \Drupal\Core\Extension\ThemeHandlerInterface|\PHPUnit\Framework\MockObject\MockObject $themeHandler */
    $themeHandler = $this->createMock(ThemeHandlerInterface::class);
    $themeHandler->expects($this->any())
      ->method('getThemeDirectories')
      ->willReturn([]);

    $cache = $this->createMock(CacheBackendInterface::class);
    $this->stringTranslation = $this->getStringTranslationStub();

    $this->uiPatternsManager = new DummyUiPatternsManager($namespaces, $cache, $moduleHandler, $themeHandler, $this->stringTranslation);
  }

  /**
   * Tests the constructor.
   *
   * @covers ::__construct
   */
  public function testConstructor(): void {
    $this->assertInstanceOf(
      UiPatternsManager::class,
      $this->uiPatternsManager
    );
  }

  /**
   * Tests the processDefinition().
   *
   * @covers ::processDefinition
   */
  public function testProcessDefinition(): void {
    $plugin_id = 'test';
    $definition = ['id' => $plugin_id];

    $expected = new PatternDefinition($definition);
    $expected->setCategory($this->stringTranslation->translate('Other'));

    /** @var \Drupal\ui_patterns\Definition\PatternDefinition $definition */
    $this->uiPatternsManager->processDefinition($definition, $plugin_id);
    $this->assertInstanceOf(PatternDefinition::class, $definition);
    $this->assertEquals($definition->toArray(), $expected->toArray());
  }

  /**
   * @covers ::getCategories
   */
  public function testGetCategories(): void {
    $this->uiPatternsManager->setPatterns([
      'id_1' => [
        'id' => 'id_1',
        'category' => 'Cat 1',
      ],
      'id_2' => [
        'id' => 'id_2',
        'category' => 'Cat 2',
      ],
      'id_3' => [
        'id' => 'id_3',
      ],
    ]);
    $expected = [
      'Cat 1',
      'Cat 2',
      'Other',
    ];
    $categories = $this->uiPatternsManager->getCategories();
    $this->assertEquals($expected, $categories);
  }

  /**
   * @covers ::getSortedDefinitions
   */
  public function testGetSortedDefinitions(): void {
    $this->uiPatternsManager->setPatterns([
      'id_z1z2' => [
        'category' => 'Z',
        'weight' => 1,
        'label' => '(Z)',
        'id' => 'id_z1z2',
      ],
      'id_z1z1' => [
        'category' => 'Z',
        'weight' => 1,
        'label' => 'Z',
        'id' => 'id_z1z1',
      ],
      'id_z1a2' => [
        'category' => 'Z',
        'weight' => 1,
        'label' => '(A)',
        'id' => 'id_z1a2',
      ],
      'id_z1a1' => [
        'category' => 'Z',
        'weight' => 1,
        'label' => 'A',
        'id' => 'id_z1a1',
      ],
      'id_z0z2' => [
        'category' => 'Z',
        'weight' => 0,
        'label' => '(Z)',
        'id' => 'id_z0z2',
      ],
      'id_z0z1' => [
        'category' => 'Z',
        'weight' => 0,
        'label' => 'Z',
        'id' => 'id_z0z1',
      ],
      'id_z0a2' => [
        'category' => 'Z',
        'weight' => 0,
        'label' => '(A)',
        'id' => 'id_z0a2',
      ],
      'id_z0a1' => [
        'category' => 'Z',
        'weight' => 0,
        'label' => 'A',
        'id' => 'id_z0a1',
      ],
      'id_a1z2' => [
        'category' => 'A',
        'weight' => 1,
        'label' => '(Z)',
        'id' => 'id_a1z2',
      ],
      'id_a1z1' => [
        'category' => 'A',
        'weight' => 1,
        'label' => 'Z',
        'id' => 'id_a1z1',
      ],
      'id_a1a2' => [
        'category' => 'A',
        'weight' => 1,
        'label' => '(A)',
        'id' => 'id_a1a2',
      ],
      'id_a1a1' => [
        'category' => 'A',
        'weight' => 1,
        'label' => 'A',
        'id' => 'id_a1a1',
      ],
      'id_a0z2' => [
        'category' => 'A',
        'weight' => 0,
        'label' => '(Z)',
        'id' => 'id_a0z2',
      ],
      'id_a0z1' => [
        'category' => 'A',
        'weight' => 0,
        'label' => 'Z',
        'id' => 'id_a0z1',
      ],
      'id_a0a2' => [
        'category' => 'A',
        'weight' => 0,
        'label' => '(A)',
        'id' => 'id_a0a2',
      ],
      'id_a0a1' => [
        'category' => 'A',
        'weight' => 0,
        'label' => 'A',
        'id' => 'id_a0a1',
      ],
    ]);

    $expected = [
      'id_a0a1',
      'id_a0a2',
      'id_a0z1',
      'id_a0z2',
      'id_a1a1',
      'id_a1a2',
      'id_a1z1',
      'id_a1z2',
      'id_z0a1',
      'id_z0a2',
      'id_z0z1',
      'id_z0z2',
      'id_z1a1',
      'id_z1a2',
      'id_z1z1',
      'id_z1z2',
    ];

    $sorted_definitions = $this->uiPatternsManager->getSortedDefinitions();
    $this->assertEquals($expected, \array_keys($sorted_definitions));
    $this->assertContainsOnlyInstancesOf(PatternDefinition::class, $sorted_definitions);
  }

  /**
   * @covers ::getGroupedDefinitions
   */
  public function testGetGroupedDefinitions(): void {
    $this->uiPatternsManager->setPatterns([
      'cat_1_1_b' => [
        'id' => 'cat_1_1_b',
        'category' => 'Cat 1',
        'label' => 'B',
        'weight' => 1,
      ],
      'cat_1_1_a' => [
        'id' => 'cat_1_1_a',
        'category' => 'Cat 1',
        'label' => 'A',
        'weight' => 1,
      ],
      'cat_1_0_a' => [
        'id' => 'cat_1_0_a',
        'category' => 'Cat 1',
        'label' => 'A',
        'weight' => 0,
      ],
      'cat_2_0_a' => [
        'id' => 'cat_1_0_a',
        'category' => 'Cat 2',
        'label' => 'A',
        'weight' => 0,
      ],
      'no_category' => [
        'id' => 'no_category',
        'label' => 'B',
        'weight' => 0,
      ],
    ]);

    $category_expected = [
      'Cat 1' => [
        'cat_1_0_a',
        'cat_1_1_a',
        'cat_1_1_b',
      ],
      'Cat 2' => [
        'cat_2_0_a',
      ],
      'Other' => [
        'no_category',
      ],
    ];

    $definitions = $this->uiPatternsManager->getGroupedDefinitions();
    $this->assertEquals(\array_keys($category_expected), \array_keys($definitions));
    foreach ($category_expected as $category => $expected) {
      $this->assertArrayHasKey($category, $definitions);
      $this->assertEquals($expected, \array_keys($definitions[$category]));
      $this->assertContainsOnlyInstancesOf(PatternDefinition::class, $definitions[$category]);
    }
  }

  /**
   * @covers ::getPatternsOptions
   */
  public function testGetPatternsOptions(): void {
    $this->uiPatternsManager->setPatterns([
      'id_1' => [
        'id' => 'id_1',
        'label' => 'Label 1',
        'category' => 'Cat 1',
      ],
      'id_2' => [
        'id' => 'id_2',
        'label' => 'Label 2',
        'category' => 'Cat 2',
      ],
      'id_3' => [
        'label' => 'Label 3',
        'id' => 'id_3',
      ],
    ]);
    $expected = [
      'Cat 1' => [
        'id_1' => 'Label 1',
      ],
      'Cat 2' => [
        'id_2' => 'Label 2',
      ],
      'Other' => [
        'id_3' => 'Label 3',
      ],
    ];
    $options = $this->uiPatternsManager->getPatternsOptions();
    $this->assertEquals($expected, $options);

    // Only one category.
    $this->uiPatternsManager->setPatterns([
      'id_1' => [
        'id' => 'id_1',
        'label' => 'Label 1',
        'category' => 'Cat 1',
      ],
      'id_2' => [
        'id' => 'id_2',
        'label' => 'Label 2',
        'category' => 'Cat 1',
      ],
    ]);
    $expected = [
      'id_1' => 'Label 1',
      'id_2' => 'Label 2',
    ];
    $options = $this->uiPatternsManager->getPatternsOptions();
    $this->assertEquals($expected, $options);
  }

}
