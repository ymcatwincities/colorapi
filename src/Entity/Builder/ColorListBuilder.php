<?php

namespace Drupal\colorapi\Entity\Builder;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Colors.
 */
class ColorListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Color');
    $header['id'] = $this->t('Machine name');
    $header['hexadecimal'] = $this->t('Hexadecimal Value');
    $header['rgb'] = $this->t('RGB Value');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    $row['hexadecimal'] = $entity->getHexadecimal();
    $row['rgb'] = $entity->getRed() . ', ' . $entity->getGreen() . ', ' . $entity->getBlue();

    return $row + parent::buildRow($entity);
  }

}
