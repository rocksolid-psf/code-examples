<?php

namespace Drupal\file_migration\Form;

use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeInterface;
use Drupal\node\NodeStorageInterface;
use Drupal\file_migration\OldStoreInterface;
use Drupal\file\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FileMigrationForm
 *
 * @package Drupal\file_migration\Form
 */
class FileMigrationForm extends FormBase {
  
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
   * The drupal 7 db.
   *
   * @var \Drupal\file_migration\OldStoreInterface
   */
  protected $oldStore;
  
  /**
   * FileMigrationForm constructor.
   *
   * @param NodeStorageInterface $node_storage
   *
   * @param OldStoreInterface $oldStore
   */
  public function __construct(NodeStorageInterface $node_storage, OldStoreInterface $oldStore) {
    $this->nodeStorage = $node_storage;
    $this->oldStore = $oldStore;
  
    $this->batchBuilder = new BatchBuilder();
  }
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getStorage('node'),
      $container->get('file_migration.connection')
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'file_migration_batch';
  }
  
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['help'] = [
      '#markup' => $this->t("This form will update all files for Product content type. </br>"),
    ];
    $form['node_type'] = [
      '#markup' => $this->t('<b>Content type:</b> Products </br>'),
    ];
    
    $fields = [
      'field_other_downloads' => $this->t('White Papers'),
      'field_technical_manuals_docs' => $this->t('Technical Manuals & Docs'),
      'field_literature_downloads' => $this->t('Literature Downloads'),
      'field_certificates' => $this->t('Certificates'),
      'field_product_dimensions' => $this->t('Product Dimensions'),
    ];
    $form['field_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Field type'),
      '#options' => $fields,
      '#required' => TRUE,
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['run'] = [
      '#type' => 'submit',
      '#value' => $this->t('Run batch'),
      '#button_type' => 'primary',
    ];
    
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get available languages.
    $languages = \Drupal::languageManager()->getLanguages();
    $languages = array_keys($languages);
    // Get field type.
    $field_type = $form_state->getValue(['field_type']);
    
    $this->batchBuilder
      ->setTitle($this->t('Processing'))
      ->setInitMessage($this->t('Initializing.'))
      ->setProgressMessage($this->t('Completed @current of @total.'))
      ->setErrorMessage($this->t('An error has occurred.'));

    $this->batchBuilder->setFile(drupal_get_path('module', 'file_migration') . '/src/Form/FileMigrationForm.php');
    
    // Set batch operation for each language.
    foreach ($languages as $lang) {
      $old_nids = $this->oldStore->getProducts($lang);
      if (!empty($old_nids)) {
        $this->batchBuilder->addOperation([$this, 'processItems'], [$field_type, $lang, $old_nids]);
      }
    }
    
    $this->batchBuilder->setFinishCallback([$this, 'finished']);

    batch_set($this->batchBuilder->toArray());
  }
  
  /**
   * Processor for batch operations.
   */
  public function processItems($field_type, $lang, $items, array &$context) {
    // Elements per operation.
    $limit = 50;
    
    // Set default progress values.
    if (empty($context['sandbox']['progress'])) {
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['max'] = count($items);
    }
    
    // Save items to array which will be changed during processing.
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
          $this->processItem($field_type, $lang, $item);
          
          $counter++;
          $context['sandbox']['progress']++;
          
          $context['message'] = $this->t('Now processing node :progress of :count', [
            ':progress' => $context['sandbox']['progress'],
            ':count' => $context['sandbox']['max'],
          ]);
          
          // Increment total processed item values. Will be used in finished
          // callback.
          $context['results']['processed'] = $context['sandbox']['progress'];
        }
      }
    }
    
    // If not finished all tasks, we count percentage of process. 1 = 100%.
    if ($context['sandbox']['progress'] != $context['sandbox']['max']) {
      $context['finished'] = $context['sandbox']['progress'] / $context['sandbox']['max'];
    }
  }
  
  /**
   * Process single item.
   *
   * @param string $item
   *   An id of Node.
   */
  public function processItem($field_type, $lang, $item) {
    $nids = $this->getNodes('products', $lang, $item->title);
    if (!empty($nids)) {
  
      switch ($field_type) {
        case 'field_other_downloads':
          $field = $this->oldStore->getOtherDownloads($item->nid);
          break;
        case 'field_technical_manuals_docs':
          $field = $this->oldStore->getTechnicalManualsDocs($item->nid);
          break;
        case 'field_literature_downloads':
          $field = $this->oldStore->getLiteratureDownloads($item->nid);
          break;
        case 'field_certificates':
          $field = $this->oldStore->getCertificates($item->nid);
          break;
        case 'field_product_dimensions':
          $field = $this->oldStore->getProductDimensions($item->nid);
          break;
      }
      
      if (count($nids) == 1) {
        $nid = reset($nids);
        $this->updateTranslation($field_type, $nid, $lang, $field);
      }
      elseif (count($nids) > 1) {
        foreach ($nids as $nid) {
          $this->updateTranslation($field_type, $nid, $lang, $field);
        }
      }
    }
  }
  
  public function updateTranslation($field_type, $nid, $lang, $fields) {
    /** @var \Drupal\node\NodeInterface $node */
    $node = $this->nodeStorage->load($nid);
    $transl_node = $node->getTranslation($lang);
  
    switch ($field_type) {
      case 'field_other_downloads':
        foreach ($fields as $field) {
          $file = $this->createFile($field->uri);
          $fld[] = [
            'target_id' => $file->id(),
            'alt' => $field->field_other_downloads_description,
            'title' => $field->field_other_downloads_description,
            'description' => $field->field_other_downloads_description,
          ];
          $transl_node->field_white_papers = $fld;
        }
        break;
      case 'field_technical_manuals_docs':
        foreach ($fields as $field) {
          $file = $this->createFile($field->uri);
          $fld[] = [
            'target_id' => $file->id(),
            'alt' => $field->field_technical_manuals_docs_description,
            'title' => $field->field_technical_manuals_docs_description,
            'description' => $field->field_technical_manuals_docs_description,
          ];
          $transl_node->field_technical_manuals_docs = $fld;
        }
        break;
      case 'field_literature_downloads':
        foreach ($fields as $field) {
          $file = $this->createFile($field->uri);
          $fld[] = [
            'target_id' => $file->id(),
            'alt' => $field->field_literature_downloads_description,
            'title' => $field->field_literature_downloads_description,
            'description' => $field->field_literature_downloads_description,
          ];
          $transl_node->field_literature_downloads = $fld;
        }
        break;
      case 'field_certificates':
        foreach ($fields as $field) {
          $file = $this->createFile($field->uri);
          $fld[] = [
            'target_id' => $file->id(),
            'alt' => $field->field_certificates_description,
            'title' => $field->field_certificates_description,
            'description' => $field->field_certificates_description,
          ];
          $transl_node->field_certificates = $fld;
        }
        break;
      case 'field_product_dimensions':
        foreach ($fields as $field) {
          $file = $this->createFile($field->uri);
          $fld[] = [
            'target_id' => $file->id(),
            'alt' => $field->field_product_dimensions_description,
            'title' => $field->field_product_dimensions_description,
            'description' => $field->field_product_dimensions_description,
          ];
          $transl_node->field_product_dimensions = $fld;
        }
        break;
    }
    
    $transl_node->save();
  }
  
  /**
   * Creates file by uri.
   *
   * @param $uri
   *   The local file uri.
   *
   * @return \Drupal\Core\Entity\EntityInterface|static
   */
  public function createFile($uri) {
    $file = File::create([
      'uri' => $uri,
    ]);
    $file->save();
    
    return $file;
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
   * Load all nids for specific type.
   *
   * @return array
   *   An array with nids.
   */
  public function getNodes($type, $langcode, $title) {
    return $this->nodeStorage->getQuery()
      ->condition('type', $type)
      ->condition('langcode', $langcode)
      ->condition('title', $title)
      ->condition('status', NodeInterface::PUBLISHED)
      ->execute();
  }
  
}
