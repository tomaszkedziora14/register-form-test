<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;



class RegistrationFormTypeTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $this->factory->addExtension(new ValidatorExtension($validator));
    }

    public function testBuildForm()
    {
        $form = $this->factory->create(RegistrationFormType::class);

        $this->assertTrue($form->has('email'));
        $this->assertTrue($form->has('agreeTerms'));
        $this->assertTrue($form->has('plainPassword'));
    }

    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'test@example.com',
            'agreeTerms' => true,
            'plainPassword' => 'password123',
        ];

        $objectToCompare = new User();
        $form = $this->factory->create(RegistrationFormType::class, $objectToCompare);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($objectToCompare->getEmail(), $formData['email']);
        $this->assertEquals($objectToCompare->getPlainPassword(), $formData['plainPassword']);
    }
}
