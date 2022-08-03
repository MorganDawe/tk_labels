<?php

namespace Drupal\tk_labels\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\views\Views;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Component\Utility\NestedArray;
use Drupal\user\UserInterface;
use Drupal\Core\Url;
use Drupal\Core\Render\Markup;
use Drupal\Core\Path\PathMatcher;

/**
 * Provides our header bar block.
 *
 * @Block(
 *   id = "tk_labels",
 *   admin_label = @Translation("TK Labels Block"),
 *   category = @Translation("IslandoraCon"),
 * )
 */
class TkLabels extends BlockBase implements BlockPluginInterface, ContainerFactoryPluginInterface {

  /**
   * Form builder, so we can build our search form.
   *
   * @var Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Menu link tree builder, so we can build our menu structure.
   *
   * @var Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuLinkTree;

  /**
   * The entity type manager, so we can load some up.
   *
   * @var Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Path matcher, used to verify current paths.
   *
   * @var Drupal\Core\Path\PathMatcher
   */
  protected $pathAliasManager;

  /**
   * The user for which we're to render the block.
   *
   * @var Drupal\user\UserEntityInterface
   */
  protected $currentUser;

  /**
   * File Storage.
   *
   * @var Drupal\file\FileStorage
   */
  protected $fileStorage;

  /**
   * Constructor.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_def, FormBuilderInterface $form_builder, MenuLinkTreeInterface $menu_link_tree, EntityTypeManagerInterface $entity_type_manager, UserInterface $current_user, PathMatcher $path_alias_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_def);

    $this->formBuilder = $form_builder;
    $this->menuLinkTree = $menu_link_tree;
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
    $this->pathAliasManager = $path_alias_manager;
    $this->fileStorage = $entity_type_manager->getStorage('file');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $entity_type_manager = $container->get('entity_type.manager');
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
      $container->get('menu.link_tree'),
      $entity_type_manager,
      $entity_type_manager->getStorage('user')->load($container->get('current_user')->id()),
      $container->get('path.matcher')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
  
    $to_return['test'] = [
      '#type' => 'checkbox',
      '#title' => t('test Checkbox?'),
    ];
    return $to_return;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    $get_value = function ($key) use ($config) {
      $found = FALSE;
      $value = NestedArray::getValue($config, (array) $key, $found);

      return $found ? $value : NULL;
    };


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
  }

}
