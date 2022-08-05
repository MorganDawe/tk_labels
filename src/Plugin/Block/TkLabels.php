<?php

namespace Drupal\tk_labels\Plugin\Block;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\views\Views;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Url;
use Drupal\Core\Render\Markup;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides our TK Labels block.
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
   * The entity type manager, so we can load some up.
   *
   * @var Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructor.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_def, FormBuilderInterface $form_builder, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_def);

    $this->formBuilder = $form_builder;
    $this->entityTypeManager = $entity_type_manager;
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
      $entity_type_manager
    );
  }

  /**
   * Evaluates if an entity has the specified term(s).
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to evalute.
   *
   * @return bool
   *   TRUE if entity has all the specified term(s), otherwise FALSE.
   */
  protected function entityHasTerm(EntityInterface $entity) {
    foreach ($entity->referencedEntities() as $referenced_entity) {
      if ($referenced_entity->getEntityTypeId() == 'taxonomy_term') {
        $field = $referenced_entity->get(IslandoraUtils::EXTERNAL_URI_FIELD);
        if (!$field->isEmpty()) {
          $link = $field->first()->getValue();
          if ($link['uri'] == $this->configuration['uri']) {
            return $this->isNegated() ? FALSE : TRUE;
          }
        }
      }
    }

    return $this->isNegated() ? TRUE : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $client = new Client();
    $to_return = [];

    $current_config = $this->configuration;

    $entity = \Drupal::routeMatch()->getParameter('node');
    $node = \Drupal::routeMatch()->getParameter('node');
    dsm($node);
    if ($entity) {
	    
      // Ensure this object has the proper field, in which to do the thing.
      if ($entity->hasField('field_notice_type') && !$entity->get('field_notice_type')->isEmpty()) {
	$notice_type = $entity->get('field_notice_type')->getValue()[0]['value'];
        dsm($notice_type);
	try {
          $request_url = $current_config['api_base_url'] . "/projects/" . $current_config['project_id'];
          $response = $client->get($request_url);
	  $result = json_decode($response->getBody(), TRUE);

	  foreach($result['notice'] as $item) {
            if ($notice_type == $item['notice_type']) {
              dsm($notice_type);
              $to_return[] = [
               '#markup' => '<img title="' . $item['default_text'] . '" src="' . $item['img_url']  . '"></img>',
              ];
	    }
	  }
        }
        catch (RequestException $e) {
          // TODO: log exception
	}
      }
    }

    return $to_return;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'project_id' => "",
      'api_base_url' => "https://anth-ja77-lc-dev-42d5.uc.r.appspot.com/api/v1"
    ];
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
    $form['project_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Project ID'),
      '#default_value' => $get_value('project_id'),
    ];
    $form['api_base_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Base URL'),
      '#default_value' => $get_value('api_base_url'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['api_base_url'] = $form_state->getValue('api_base_url');
    $this->configuration['project_id'] = $form_state->getValue('project_id');
  }

}

