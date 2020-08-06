<?php

namespace Drupal\parent_language\Form;

use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\node\NodeStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ParentLanguageForm.
 *
 * @package Drupal\parent_language\Form
 */
class ParentLanguageForm extends FormBase {

  /**
   * Batch Builder.
   *
   * @var \Drupal\Core\Batch\BatchBuilder
   */
  protected $batchBuilder;

  /**
   * Node storage.
   *
   * @var \Drupal\node\NodeStorageInterface
   */
  protected $nodeStorage;

  /**
   * Term storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $paragraphStorage;

  /**
   * Node type storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeTypeStorage;

  /**
   * Language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * ParentLanguageForm constructor.
   *
   * @param \Drupal\node\NodeStorageInterface $node_storage
   *   Node storage.
   * @param \Drupal\Core\Entity\EntityStorageInterface $paragraph_storage
   *   Paragraph storage.
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   *   Language manager.
   * @param \Drupal\Core\Entity\EntityStorageInterface $nodeTypeStorage
   *   Node type storage.
   */
  public function __construct(NodeStorageInterface $node_storage, EntityStorageInterface $paragraph_storage, LanguageManagerInterface $languageManager, EntityStorageInterface $nodeTypeStorage) {
    $this->nodeStorage = $node_storage;
    $this->paragraphStorage = $paragraph_storage;
    $this->languageManager = $languageManager;
    $this->nodeTypeStorage = $nodeTypeStorage;
    $this->batchBuilder = new BatchBuilder();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getStorage('node'),
      $container->get('entity_type.manager')->getStorage('paragraph'),
      $container->get('language_manager'),
      $container->get('entity_type.manager')->getStorage('node_type')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'parent_language_batch';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['help'] = [
      '#markup' => $this->t("This form will change node`s parent language to language selected in input 'New parent language for node'. </br>"),
    ];
    $languages = [];
    $bundles = [];
    $lang = $this->languageManager->getLanguages();
    foreach ($lang as $key => $value) {
      $languages[$key] = $value->getName();
    }
    $types = $this->nodeTypeStorage->loadMultiple();
    foreach ($types as $key => $value) {
      $bundles[$key] = $value->label();
    }
    $form['bundle'] = [
      '#type' => 'select',
      '#options' => $bundles,
      '#title' => $this->t('Node type'),
      '#description' => $this->t('Select nodes type'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::loadNode',
        'wrapper' => 'node-table',
      ],
    ];
    $form['language'] = [
      '#type' => 'select',
      '#options' => $languages,
      '#title' => $this->t('New parent language for node'),
      '#description' => $this->t('Select new parent language for nodes'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::loadNode',
        'wrapper' => 'node-table',
      ],
    ];
    $bundle = $form_state->getValue('bundle');
    $language = $form_state->getValue('language');
    $nid_to_show = [];
    if (isset($bundle) && isset($language)) {
      $query = $this->nodeStorage->getQuery()
        ->condition('type', $bundle, '=')
        ->condition('langcode', $language, '!=')
        ->condition('default_langcode', '1', '=');
      $nids = $query->execute();
      if ($nids) {
        $nid_to_show = [];
        $empty = '';
        foreach ($nids as $nid) {
          $node = $this->nodeStorage->load($nid);
          $language = $form_state->getValue('language');
          if ($node->hasTranslation($language)) {
            $nid_to_show[$node->id()] = [
              'node_id' => $node->id(),
              'node_title' => $node->label(),
            ];
          }
        }
      }
      else {
        $empty = $this->t('Not found any nodes.');
      }
    }
    else {
      $empty = $this->t('Select node type and parent language.');
      $nid_to_show = [];
    }
    $header = [
      'node_id' => $this->t('Node ID'),
      'node_title' => $this->t('Node title'),
    ];
    $form['help_node'] = [
      '#markup' => $this->t('Nodes that don`t have parent language selected on the field, but have this language translation.'),
    ];
    $form['nid'] = [
      '#type' => 'tableselect',
      '#prefix' => '<div id="node-table">',
      '#suffix' => '</div>',
      '#header' => $header,
      '#options' => $nid_to_show,
      '#title' => $this->t('Node nid'),
      '#description' => $this->t('Nodes that don`t have selected parent language, but have selected language translation'),
      '#empty' => $empty,
      '#required' => TRUE,
    ];
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['run'] = [
      '#type' => 'submit',
      '#value' => $this->t('Edit parent language batch'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * Ajax callback.
   *
   * @param array $form
   *   Form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   *
   * @return mixed
   *   Update node select field.
   */
  public function loadNode(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild();
    return $form['nid'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nids = $form_state->getValue(['nid']);
    $language = $form_state->getValue('language');
    $node = [];
    foreach ($nids as $nid) {
      if ($nid != 0) {
        $node[] = $nid;
      }
    }
    $this->batchBuilder
      ->setTitle($this->t('Processing'))
      ->setInitMessage($this->t('Initializing.'))
      ->setProgressMessage($this->t('Completed @current of @total.'))
      ->setErrorMessage($this->t('An error has occurred.'));
    $this->batchBuilder->setFile(drupal_get_path('module', 'parent_language') . '/src/Form/ParentLanguageForm.php');
    $this->batchBuilder->addOperation([$this, 'processItems'],
      [$node, $language]);
    $this->batchBuilder->setFinishCallback([$this, 'finished']);
    batch_set($this->batchBuilder->toArray());
  }

  /**
   * Processor for batch operations.
   *
   * @param array $items
   *   Node nids.
   * @param string $language
   *   New node parent language.
   * @param array $context
   *   Context.
   */
  public function processItems(array $items, $language, array &$context) {
    $limit = 1;
    if (empty($context['sandbox']['progress'])) {
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['max'] = count($items);
    }
    if (empty($context['sandbox']['items'])) {
      $context['sandbox']['items'] = $items;
    }
    $counter = 0;
    if (!empty($context['sandbox']['items'])) {
      // Remove already processed items.
      if ($context['sandbox']['progress'] != 0) {
        array_splice($context['sandbox']['items'], 0, $limit);
      }
      foreach ($context['sandbox']['items'] as $item) {
        if ($counter != $limit) {
          $this->processItem($item, $language);
          $counter++;
          $context['sandbox']['progress']++;
          $context['message'] = $this->t('Now processing node :progress of :count', [
            ':progress' => $context['sandbox']['progress'],
            ':count' => $context['sandbox']['max'],
          ]);
          $context['results']['processed'] = $context['sandbox']['progress'];
        }
      }
    }
    if ($context['sandbox']['progress'] != $context['sandbox']['max']) {
      $context['finished'] = $context['sandbox']['progress'] / $context['sandbox']['max'];
    }
  }

  /**
   * Process single item.
   *
   * @param int $nid
   *   Node nid.
   * @param string $language_to_keep
   *   New node parent language.
   */
  public function processItem($nid, $language_to_keep) {
    $node = $this->nodeStorage->load($nid);
    $language_to_remove = $node->language()->getId();
    $this->changeParentLanguage($node, $language_to_remove, $language_to_keep);
  }

  /**
   * Finished callback for batch.
   */
  public function finished($success, $results, $operations) {
    $message = $this->t('Number of nodes affected by last batch operation: @count', [
      '@count' => $results['processed'],
    ]);
    $this->messenger()
      ->addStatus($message);
  }

  /**
   * Edit parent language for node.
   *
   * @param object $entity
   *   Node object.
   * @param string $langcode_to_remove
   *   Current parent language.
   * @param string $langcode_to_keep
   *   New parent language.
   *
   * @return bool
   *   Return TRUE.
   */
  public function changeParentLanguage($entity, $langcode_to_remove, $langcode_to_keep) {
    if ($entity->language()->getId() != $langcode_to_remove) {
      return FALSE;
    }
    if (!$entity->hasTranslation($langcode_to_keep)) {
      return FALSE;
    }
    $values_to_keep = $entity->getTranslation($langcode_to_keep)->toArray();
    $current_language = $entity->getTranslation($langcode_to_remove)->toArray();
    $field_definitions = $entity->getFieldDefinitions();
    foreach ($field_definitions as $field_name => $field_definition) {
      if ($field_definition->getSetting('handler') == 'default:paragraph') {
        foreach ($values_to_keep[$field_name] as $paragraph_reference) {
          $paragraph = $this->paragraphStorage->loadRevision($paragraph_reference['target_revision_id']);
          $this->changeParentLanguage($paragraph, $langcode_to_remove, $langcode_to_keep);
        }
      }
    }
    $entity->removeTranslation($langcode_to_keep);
    $entity->save();
    $entity->set('langcode', $langcode_to_keep);
    foreach ($values_to_keep as $field_name => $field_values) {
      if ($entity->getFieldDefinition($field_name)->isTranslatable() && !in_array($field_name, ['default_langcode'])) {
        $entity->set($field_name, $field_values);
      }
    }
    $entity->save();
    $entity->addTranslation($langcode_to_remove, $current_language);
    $entity->save();
    return TRUE;
  }

}
