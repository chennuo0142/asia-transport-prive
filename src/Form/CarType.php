<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\User;
use Imagine\Gd\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Image as ConstraintsImage;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand')
            ->add('model')
            ->add('color')
            ->add('buyAt', null, [
                'widget' => 'single_text',
            ])
            ->add('seats')

            ->add('energy', ChoiceType::class, [
                'choices' => [
                    'Diesel' => 'Diesel',
                    'Essence' => 'Essence',
                    'Hybride' => 'Hybride',
                    'Electric' => 'Electric',
                    'Autre' => 'Autre',

                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Energie:'
            ])
            ->add('maxBagage')
            ->add('licensePlate')
            ->add('photoImage', FileType::class, [
                'mapped' => false,
                'label' => false,
                'required' => false,
                'constraints' => [
                    new ConstraintsImage()
                ]
            ])
            ->add('galery', FileType::class, [
                'mapped' => false,
                'label' => false,
                'multiple' => true,
                'required' => false,

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
