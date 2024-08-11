<?php

namespace App\Form;

use App\Entity\ProfileUser;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image as ConstraintsImage;


class ProfileUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('firstName')
            ->add('pseudo')
            ->add('photoImage', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new ConstraintsImage()
                ]
            ])
            ->add('birthday', null, [
                'widget' => 'single_text',
            ])
            ->add('adress')
            ->add('city')
            ->add('country')
            ->add('email')
            ->add('telephone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProfileUser::class,
        ]);
    }
}
