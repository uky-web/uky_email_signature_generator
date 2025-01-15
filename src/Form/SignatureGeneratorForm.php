<?php

namespace Drupal\uky_email_signature_generator\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Renderer;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class SignatureGeneratorForm.
 */
class SignatureGeneratorForm extends FormBase {
  protected Renderer $renderer;

  public function __construct(Renderer $renderer) {
    $this->renderer = $renderer;
  }

  public static function create(ContainerInterface $container): SignatureGeneratorForm {
    return new static(
      $container->get('renderer')
    );
  }

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

    $form['credentials'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Credentials'),
      '#placeholder' => $this->t('Ph.D'),
    ];

    $pronouns = [
      'nom' => [
        'she',
        'he',
        'they',
      ],
      'obj' => [
        'her',
        'him',
        'them',
      ],
      'pos' => [
        'hers',
        'his',
        'theirs',
      ],
    ];

    $pronoun_placeholder = implode('/', [
      array_rand(array_flip($pronouns['nom'])),
      array_rand(array_flip($pronouns['obj'])),
      array_rand(array_flip($pronouns['pos']))
    ]);

    $form['pronouns'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pronouns'),
      '#placeholder' => $this->t($pronoun_placeholder . ' etc.'),
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
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate Signature'),
      '#ajax' => [
        'callback' => '::ajaxSubmitCallback',
        'wrapper' => 'result-wrapper',
      ],
    ];

    return $form;
  }

  /**
   * AJAX callback for the form submission.
   */
  public function ajaxSubmitCallback(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $data = [
      '#theme' => 'signature_result',
      '#data' => [
        'name' => implode(' ', [$form_values['first_name'], $form_values['last_name']]),
        'creds' => $form_values['credentials'],
        'pronouns' => $form_values['pronouns'],
        'title' => $form_values['position_title'],
        'university' => 'University of Kentucky',
        'department' => $form_values['department_unit'],
        'sub_unit' => $form_values['sub_unit'],
        'address' => $form_values['address'],
        'city' => $form_values['city_state_zip'],
        'phone' => $form_values['phone'],
        'email' => $form_values['email'],
      ],
    ];

    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#result-wrapper', $this->renderer->render($data)));
    return $response;
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
