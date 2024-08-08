<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Driver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image as ConstraintsImage;

class DriverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('slug')
            // ->add('userId')
            // ->add('createAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updateAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('name')
            ->add('firstName')
            ->add('birthDay', null, [
                'widget' => 'single_text',
            ])
            ->add('adress')
            ->add('zipCode')
            ->add('city')
            ->add('country')
            ->add('telephone')
            ->add('email')
            ->add('language', ChoiceType::class, [
                'choices' => [
                    'Français' => 'Français',
                    'Anglais' => 'Anglais',
                    'Italien' => 'Italien',
                    'Espagnole' => 'Espagnole',
                    'Allemand' => 'Allemand',
                    'Mandarin' => 'Mandarin',
                    'Arabe' => 'Arabe'
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Langue parler:'
            ])
            ->add('photoImage', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new ConstraintsImage()
                ]
            ])

            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Driver::class,
        ]);
    }
}
