<?php

namespace App\Form;

use App\Entity\ContactMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactMessage1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('subject')
            ->add('email')
            ->add('message')
            ->add('createAt', null, [
                'widget' => 'single_text',
            ])
            ->add('readAt', null, [
                'widget' => 'single_text',
            ])
            ->add('answeredAt', null, [
                'widget' => 'single_text',
            ])
            ->add('treatBy')
            ->add('slug')
            ->add('phone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactMessage::class,
        ]);
    }
}
