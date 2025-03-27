<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Tables;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class ReservationType3 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tables', ChoiceType::class, [
                'choices' => $options['tables'],
                'choice_label' => function(Tables $tables) {
                    return $tables->getNbPlaces() . ' personnes';
                },
                'label' => 'Nombre de personnes :',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium',
                ],
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);

        $resolver->setRequired(['tables']);
    }
}
