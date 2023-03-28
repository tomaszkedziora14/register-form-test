<?php

namespace App\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;


class RegistrationFormTypeFunctionalTest extends WebTestCase
{
    public function testSubmitValidData()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form([
            'registration_form[email]' => 'test@example.com',
            'registration_form[agreeTerms]' => true,
            'registration_form[plainPassword]' => 'password123',
        ]);

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/'));
    }

    public function testSubmitInvalidData()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form([
            'registration_form[email]' => 'invalidemail',
            'registration_form[agreeTerms]' => false,
            'registration_form[plainPassword]' => 'short',
        ]);

        $client->submit($form);

        $this->assertContains('This value is not a valid email address.', $client->getResponse()->getContent());
        $this->assertContains('You should agree to our terms.', $client->getResponse()->getContent());
        $this->assertContains('Your password should be at least 6 characters', $client->getResponse()->getContent());
    }
}
