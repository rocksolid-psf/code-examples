<?php

/**
 * @file
 * Contains \Drupal\ss_location\LocationListBuilder.
 */

namespace Drupal\ss_location;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;

/**
 * Defines a class to build a listing of Location entities.
 *
 * @ingroup ss_location
 */
class LocationListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Location ID');
    $header['name'] = $this->t('Name');
    $header['status'] = $this->t('Status');
    $header['path'] = $this->t('Path');
    $header['manager'] = $this->t('Manager');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var $entity \Drupal\ss_location\Entity\Location */
    $row['id'] = $entity->id();
    $row['name']['data'] = [
      '#type' => 'link',
      '#title' => $entity->getName(),
      '#url' => $entity->toUrl('canonical')
    ];
    $row['status'] = ($entity->getStatus() == 0) ? t('Unpublished') : t('Published');
    $row['path'] = $entity->getPath();
    $row['manager'] = @$entity->getManagerInfo()->FirstName . ' ' . @$entity->getManagerInfo()->LastName;
    return $row + parent::buildRow($entity);
  }

}
