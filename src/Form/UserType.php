<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email :',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium lg:text-lg',
                ],
                'attr' => [
                    'class' => 'lg:text-lg bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white mb-3',
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom de réservation :',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium lg:text-lg',
                ],
                'attr' => [
                    'class' => 'lg:text-lg bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white mb-3',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe :',
                    'label_attr' => [
                        'class' => 'block mb-2 text-sm font-medium lg:text-lg',
                    ],
                    'attr' => [
                        'class' => 'lg:text-lg bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white mb-3',
                    ],
                    'constraints' => [
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                            'max' => 4096,
                        ]),
                    ],
                ],
                'required' => false,
                'second_options' => [
                    'label' => 'Confirmez le mot de passe :',
                    'label_attr' => [
                        'class' => 'block mb-2 text-sm font-medium lg:text-lg',
                    ],
                    'attr' => [
                        'class' => 'lg:text-lg bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white mb-3',
                    ],
                ],
                'invalid_message' => 'Les mots de passes doivent être les mêmes.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
