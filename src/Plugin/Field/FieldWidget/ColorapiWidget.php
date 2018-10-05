<?php

namespace Drupal\colorapi\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\WidgetInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * The default widget for Color fields.
 *
 * @FieldWidget(
 *   id = "colorapi_color_widget",
 *   label = @Translation("Textfield Input"),
 *   field_types = {
 *      "colorapi_color_field"
 *   }
 * )
 */
class ColorapiWidget extends WidgetBase implements WidgetInterface {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {

    $summary = [];

    $summary['overview'] = $this->t('A textfield input that accepts a hexadecimal color string');

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element['name'] = [
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#default_value' => isset($items[$delta]) ? $items[$delta]->getColorName() : '',
      '#cardinality' => $this->fieldDefinition->getFieldStorageDefinition()->getCardinality(),
    ];

    $element['color'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Color'),
      '#default_value' => isset($items[$delta]) ? $items[$delta]->getHexadecimal() : '',
      '#cardinality' => $this->fieldDefinition->getFieldStorageDefinition()->getCardinality(),
    ];

    return $element;
  }

}
