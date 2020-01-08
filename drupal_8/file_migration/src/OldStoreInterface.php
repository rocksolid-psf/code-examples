<?php

namespace Drupal\file_migration;

/**
 * Interface OldStoreInterface.
 */
interface OldStoreInterface {
  
  /**
   * Provides a node ids.
   *
   * @param $lang
   *   The lang code.
   *
   * @return array
   */
  public function getProducts($lang);
  
  /**
   * Provides field_literature_downloads_fid value.
   *
   * @param $nid
   *   The node id.
   *
   * @return array
   */
  public function getLiteratureDownloads($nid);
  
  /**
   * Provides field_other_downloads value.
   *
   * @param $nid
   *   The node id.
   *
   * @return array
   */
  public function getOtherDownloads($nid);
  
  /**
   * Provides technical_manuals_docs value.
   *
   * @param $nid
   *   The node id.
   *
   * @return array
   */
  public function getTechnicalManualsDocs($nid);
  
  /**
   * Provides field_certificates value.
   *
   * @param $nid
   *   The node id.
   *
   * @return array
   */
  public function getCertificates($nid);
  
  /**
   * Provides field_product_dimensions value.
   *
   * @param $nid
   *   The node id.
   *
   * @return array
   */
  public function getProductDimensions($nid);
  
}
