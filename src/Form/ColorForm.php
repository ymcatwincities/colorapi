<?php

namespace Drupal\colorapi\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\TypedDataManagerInterface;
use Drupal\colorapi\Plugin\DataType\HexColorInterface;
use Drupal\colorapi\Service\ColorapiServiceInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form handler for the Color add and edit forms.
 */
class ColorForm extends EntityForm {

  /**
   * The Typed Data manager.
   *
   * @var \Drupal\Core\TypedData\TypedDataManagerInterface
   */
  protected $typedDataManager;

  /**
   * The Color API service.
   *
   * @var \Drupal\colorapi\DataType\ColorapiServiceInterface
   */
  protected $colorapiService;

  /**
   * Constructs a ColorForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entityTypeManager.
   * @param \Drupal\Core\TypedData\TypedDataManagerInterface $typedDataManager
   *   The Typed Data manager.
   * @param \Drupal\colorapi\DataType\ColorapiServiceInterface $colorapiService
   *   The Color API service.
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    TypedDataManagerInterface $typedDataManager,
    ColorapiServiceInterface $colorapiService
  ) {
    $this->entityTypeManager = $entityTypeManager;
    $this->typedDataManager = $typedDataManager;
    $this->colorapiService = $colorapiService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('typed_data_manager'),
      $container->get('colorapi.service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $color_entity = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $color_entity->label(),
      '#description' => $this->t("Label for the Color."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $color_entity->id(),
      '#machine_name' => [
        'exists' => [$this, 'checkMachineName'],
      ],
      '#disabled' => !$color_entity->isNew(),
    ];

    $form['color'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Color'),
      '#default_value' => $color_entity->getHexadecimal(),
      '#required' => TRUE,
      '#description' => $this->t('Enter the color in hexadecimal string format #XXXXXX where X is a hexadecimal character (0-9, a-f).'),
      '#element_validate' => ['::colorElementValidate'],
    ];

    return $form;
  }

  /**
   * Custom element validation handler for the 'color' property.
   *
   * Creates a TypedDataAPI colorapi_color object to work with, setting it as
   * the value for the element.
   */
  public function colorElementValidate(array &$element, FormStateInterface $form_state) {
    $hexadecimal_color = $form_state->getValue('color');
    if (!preg_match(HexColorInterface::HEXADECIMAL_COLOR_REGEX, $hexadecimal_color)) {
      $form_state->setError($element, $this->t('%value is not a valid hexadecimal color string. Hexadecimal color strings are in the format #XXXXXX where X is a hexadecimal character (0-9, a-f).', ['%value' => $hexadecimal_color]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $color_entity = &$this->entity;
    $status = $color_entity->save();

    if ($status) {
      drupal_set_message($this->t('Saved the Color: %label.', [
        '%label' => $color_entity->label(),
      ]));
    }
    else {
      drupal_set_message($this->t('The Color %label was not saved.', [
        '%label' => $color_entity->label(),
      ]));
    }

    $form_state->setRedirect('entity.colorapi_color.collection');
  }

  /**
   * Helper function to check whether the Color machine_name already exists.
   *
   * @param string $id
   *   The Entity machine name to check for existence.
   *
   * @return bool
   *   A boolean indicating whether or not an entity with the given machine name
   *   exists or not.
   */
  public function checkMachineName($id) {
    $entity = $this->entityTypeManager->getStorage('colorapi_color')->getQuery()
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

}
