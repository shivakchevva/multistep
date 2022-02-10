<?php

/**
 * @file
 * Contains \Drupal\custom_multistep\Form\Multistep\MultistepTwoForm.
 */

namespace Drupal\custom_multistep\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class MultistepTwoForm extends MultistepFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'multistep_form_two';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    $form['fullname'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Full name'),
      '#default_value' => $this->store->get('fullname') ? $this->store->get('fullname') : '',
      '#required' => TRUE,
    );

    $form['dob'] = array(
      '#type' => 'date',
      '#title' => 'Custom date',
      '#date_date_format' => 'Y-m-d',
      '#format' => 'm/d/Y',
      '#default_value' => $this->store->get('dob') ? $this->store->get('dob') : '',
      '#required' => TRUE,
    );

    $form['country_of_residence'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Country of residence'),
      '#default_value' => $this->store->get('country_of_residence') ? $this->store->get('country_of_residence') : '',
      '#required' => TRUE,
    );

    $form['actions']['previous'] = array(
      '#type' => 'link',
      '#title' => $this->t('Previous'),
      '#attributes' => array(
        'class' => array('button'),
      ),
      '#weight' => 0,
      '#url' => Url::fromRoute('custom_multistep.multistep_one'),
    );

    $form['#cache'] = ['max-age' => 0];
    $form['actions']['submit']['#value'] = $this->t('Next');
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->store->set('fullname', $form_state->getValue('fullname'));
    $this->store->set('dob', $form_state->getValue('dob'));
    $this->store->set('country_of_residence', $form_state->getValue('country_of_residence'));
    $form_state->setRedirect('custom_multistep.multistep_three');
  }
}
