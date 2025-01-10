<?php

namespace Drupal\uky_email_signature_generator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SignatureGeneratorForm.
 */
class SignatureGeneratorForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'signature_generator_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#placeholder' => $this->t('John'),
      '#required' => TRUE,
    ];

    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
      '#placeholder' => $this->t('Smith'),
      '#required' => TRUE,
    ];

    $form['pronouns'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pronouns'),
      '#placeholder' => $this->t('she/her, he/him, they/them, etc.'),
    ];

    $form['credentials'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Credentials'),
      '#placeholder' => $this->t('Ph.D'),
    ];

    $form['position_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Position/Title'),
      '#placeholder' => $this->t('Director'),
    ];

    $form['department_unit'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Department/Unit'),
      '#placeholder' => $this->t('Department/Unit'),
    ];

    $form['sub_unit'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Sub-Unit'),
      '#placeholder' => $this->t('Sub-Unit/Team'),
    ];

    $form['address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Address'),
      '#placeholder' => $this->t('123 Main St.'),
    ];

    $form['city_state_zip'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City, State ZIP'),
      '#placeholder' => $this->t('Lexington, KY 40506'),
    ];

    $form['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone'),
      '#placeholder' => $this->t('859.555.5555'),
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#placeholder' => $this->t('john.smith@example.com'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate Signature'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    if (empty($form_state->getValue('first_name'))) {
      $form_state->setErrorByName('first_name', $this->t('First Name is required.'));
    }
    if (empty($form_state->getValue('last_name'))) {
      $form_state->setErrorByName('last_name', $this->t('Last Name is required.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Handle form submission.
    \Drupal::messenger()->addMessage($this->t('Signature generated for @first_name @last_name.', [
      '@first_name' => $form_state->getValue('first_name'),
      '@last_name' => $form_state->getValue('last_name'),
    ]));
  }
}
