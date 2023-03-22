<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class DuckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new Length(['min' => 3,
                    'max' => 100,
                    'minMessage' => 'min 3 letters',
                    'maxMessage' => 'max 100']),
                    new NotBlank(['message' => 'Not empty field']),
                    new Regex(['pattern' =>'/^[a-zA-Z0-9]+$/', 'message' => 'Only letter a-z A-Z and number 0-9'])
                ]
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new Length(['min' => 3,
                        'max' => 100,
                        'minMessage' => 'min 3 letters',
                        'maxMessage' => 'max 100']),
                    new NotBlank(['message' => 'Not empty field']),
                    new Regex(['pattern' =>'/^[a-zA-Z0-9]+$/', 'message' => 'Only letter a-z A-Z and number 0-9'])
                ]
            ])
            ->add('duckName', TextType::class, [
                'constraints' => [
                    new Length(['min' => 3,
                        'max' => 100,
                        'minMessage' => 'min 3 letters',
                        'maxMessage' => 'max 100']),
                    new NotBlank(['message' => 'Not empty field']),
                    new Regex(['pattern' =>'/^[a-zA-Z0-9]+$/', 'message' => 'Only letter a-z A-Z and number 0-9'])
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(message: 'Only valid email'),
                    new NotBlank(['message' => 'Not empty field'])
                ]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new Length(['min' => 3,
                        'minMessage' => 'min 3 letters',
                        'maxMessage' => 'max 100']),
                    new NotBlank(['message' => 'Not empty field'])
                ]
            ]);

    }
}