<?php

namespace Drupal\file_migration;

use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class OldStore.
 */
class OldStore implements OldStoreInterface {
  
  /**
   * Drupal\Core\Database\Driver\pgsql\Connection definition.
   *
   * @var \Drupal\Core\Database\Driver\mysql\Connection
   */
  protected $database;
  
  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;
  
  /**
   * OldStore constructor.
   *
   * @param \Drupal\Core\Database\Driver\mysql\Connection $database
   *   Database connection object.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   entityTypeManager object.
   */
  public function __construct(Connection $database, EntityTypeManagerInterface $entityTypeManager) {
    $this->database = $database;
    $this->entityTypeManager = $entityTypeManager;
  }
  
  /**
   * {@inheritdoc}
   */
  public function getProducts($lang) {
    $query = $this->database->select('node', 'nd');
    $query->fields('nd', ['nid', 'title']);
    $query->condition('type', 'product');
    $query->condition('language', $lang);
    $query->orderBy('nid');
    $items = $query->execute()->fetchAll();
    return empty($items) ? [] : $items;
  }

  /**
   * {@inheritdoc}
   */
  public function getLiteratureDownloads($nid) {
    $query = $this->database->select('field_data_field_literature_downloads', 'fdfld');
    $query->fields('fdfld', ['field_literature_downloads_fid', 'field_literature_downloads_description']);
    $query->join('file_managed', 'fm', 'fdfld.field_literature_downloads_fid = fm.fid');
    $query->fields('fm', ['filename', 'uri']);
    $query->condition('fdfld.entity_id', $nid);
    $query->condition('fdfld.bundle', 'product');
    $items = $query->execute()->fetchAll();
    return empty($items) ? [] : $items;
  }

  /**
   * {@inheritdoc}
   */
  public function getOtherDownloads($nid) {
    $query = $this->database->select('field_data_field_other_downloads', 'fdfod');
    $query->fields('fdfod', ['field_other_downloads_fid', 'field_other_downloads_description']);
    $query->join('file_managed', 'fm', 'fdfod.field_other_downloads_fid = fm.fid');
    $query->fields('fm', ['filename', 'uri']);
    $query->condition('fdfod.entity_id', $nid);
    $query->condition('fdfod.bundle', 'product');
    $items = $query->execute()->fetchAll();
    return empty($items) ? [] : $items;
  }

  /**
   * {@inheritdoc}
   */
  public function getTechnicalManualsDocs($nid) {
    $query = $this->database->select('field_data_field_technical_manuals_docs', 'fdftmd');
    $query->fields('fdftmd', ['field_technical_manuals_docs_fid', 'field_technical_manuals_docs_description']);
    $query->join('file_managed', 'fm', 'fdftmd.field_technical_manuals_docs_fid = fm.fid');
    $query->fields('fm', ['filename', 'uri']);
    $query->condition('fdftmd.entity_id', $nid);
    $query->condition('fdftmd.bundle', 'product');
    $items = $query->execute()->fetchAll();
    return empty($items) ? [] : $items;
  }

  /**
   * {@inheritdoc}
   */
  public function getCertificates($nid) {
    $query = $this->database->select('field_data_field_certificates', 'fdfc');
    $query->fields('fdfc', ['field_certificates_fid', 'field_certificates_description']);
    $query->join('file_managed', 'fm', 'fdfc.field_certificates_fid = fm.fid');
    $query->fields('fm', ['filename', 'uri']);
    $query->condition('fdfc.entity_id', $nid);
    $query->condition('fdfc.bundle', 'product');
    $items = $query->execute()->fetchAll();
    return empty($items) ? [] : $items;
  }

  /**
   * {@inheritdoc}
   */
  public function getProductDimensions($nid) {
    $query = $this->database->select('field_data_field_product_dimensions', 'fdfpd');
    $query->fields('fdfpd', ['field_product_dimensions_fid', 'field_product_dimensions_description']);
    $query->join('file_managed', 'fm', 'fdfpd.field_product_dimensions_fid = fm.fid');
    $query->fields('fm', ['filename', 'uri']);
    $query->condition('fdfpd.entity_id', $nid);
    $query->condition('fdfpd.bundle', 'product');
    $items = $query->execute()->fetchAll();
    return empty($items) ? [] : $items;
  }

}
