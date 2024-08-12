<?php

namespace App\Form;

use App\Entity\CarCategory;
use App\Entity\Reservation;
use App\Entity\ServiceListe;
use App\Entity\AccessoryListe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('firstName')
            ->add('compagny')
            ->add('email')
            ->add('telephone')
            ->add('service', EntityType::class, [
                'class' => ServiceListe::class,
                'choice_label' => 'name',
            ])
            ->add('dateOperation')
            ->add('timeOperation')
            ->add('car', EntityType::class, [
                'class' => CarCategory::class,
                'choice_label' => 'name',
            ])
            ->add('departureAdress')
            ->add('ArrivalAdress')
            ->add('nbPassager')
            ->add('nbBagage')
            ->add('accessory', ChoiceType::class, [
                'choices' => [
                    'Bouteil d\'eau' => 'Bouteil d\'eau',
                    'Siège enfant' => 'Siège enfant',
                    'Chargeur téléphone' => 'Chargeur téléphone',
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Accessoir:'
            ])
            ->add('numTransport')
            ->add('message', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
