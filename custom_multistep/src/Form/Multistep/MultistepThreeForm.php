<?php

/**
 * @file
 * Contains \Drupal\custom_multistep\Form\Multistep\MultistepTwoForm.
 */

namespace Drupal\custom_multistep\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class MultistepThreeForm extends MultistepFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'multistep_form_three';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    $options = array('pepperoni' => 'pepperoni', 'cheese' => 'cheese', 'meatlovers' => 'meatlovers', 'vegetarian' => 'vegetarian', 'Hawaiian' => 'Hawaiian');
    $form['favorite_type_pizza'] = array(
      '#type' => 'select',
      '#title' => $this->t('Favorite type of pizza'),
      '#options' => $options,
      '#default_value' => $this->store->get('favorite_type_pizza') ? $this->store->get('favorite_type_pizza') : '',
    );

    $form['actions']['previous'] = array(
      '#type' => 'link',
      '#title' => $this->t('Previous'),
      '#attributes' => array(
        'class' => array('button'),
      ),
      '#weight' => 0,
      '#url' => Url::fromRoute('custom_multistep.multistep_two'),
    );
    $form['#cache'] = ['max-age' => 0];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->store->set('favorite_type_pizza', $form_state->getValue('favorite_type_pizza'));

    // Save the data
    parent::saveData();
    $form_state->setRedirect('custom_multistep.multistep_one');
  }
}