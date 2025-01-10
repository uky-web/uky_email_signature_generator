<?php
namespace Drupal\uky_email_signature_generator\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\uky_email_signature_generator\Form\SignatureGeneratorForm;

/**
 * Provides route responses for the Example module.
 */
class GeneratorFormController extends ControllerBase {
  /**
   * The form builder.
   *
   * @var FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Constructs a GeneratorFormController object.
   *
   * @param FormBuilderInterface $form_builder
   *   The form builder.
   */
  public function __construct(FormBuilderInterface $form_builder) {
    $this->formBuilder = $form_builder;
  }

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function page(): array {
    // Render the form.
    return $this->formBuilder->getForm(SignatureGeneratorForm::class);
  }
}
