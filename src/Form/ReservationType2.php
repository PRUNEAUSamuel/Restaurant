<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;

class ReservationType2 extends AbstractType
{


    private function getAllValidSlots(): array
    {
        $slots = [];

        // Plage midi
        for ($hour = 12; $hour < 14; $hour++) {
            foreach ([0, 15, 30, 45] as $min) {
                $str = sprintf('%02d:%02d', $hour, $min);
                $slots[$str] = $str;
            }
        }

        // Plage soir
        for ($hour = 19; $hour < 21; $hour++) {
            foreach ([0, 15, 30, 45] as $min) {
                $str = sprintf('%02d:%02d', $hour, $min);
                $slots[$str] = $str;
            }
        }

        return $slots;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('arrivalTime', ChoiceType::class, [
                'required' => true,
                'label' => 'Heure d\'arrivée :',
                'choices' => $this->getAllValidSlots(),
                'placeholder' => 'Sélectionnez une heure',
                'label_attr' => [
                    'class' => 'block mt-3 text-sm font-medium',
                ],
                'attr' => [
                    'class' => 'mt-4 mb-6 arrival-time-select bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white',
                ],
            ]);

        // Transformer string "12:30" <-> DateTimeImmutable("12:30")
        $builder->get('arrivalTime')->addModelTransformer(new CallbackTransformer(
            // Model → Form : DateTimeImmutable → "H:i"
            function ($valueFromModel) {
                return $valueFromModel instanceof \DateTimeInterface
                    ? $valueFromModel->format('H:i')
                    : null;
            },
        
            // Form → Model : "H:i" → DateTimeImmutable
            function ($valueFromView) {
                if (!$valueFromView) return null;
        
                try {
                    $dt = \DateTimeImmutable::createFromFormat('H:i', $valueFromView);
                    return $dt ?: null;
                } catch (\Exception $e) {
                    return null;
                }
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'reservation';
    }
}
