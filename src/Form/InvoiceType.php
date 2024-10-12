<?php

namespace App\Form;

use App\Entity\Invoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateOperation', null, [
                'widget' => 'single_text',
            ])
            ->add('timeOperation', null, [
                'widget' => 'single_text',
            ])
            ->add('firstName', TextType::class, [
                "mapped" => false,
            ])
            ->add('lastName', TextType::class, [
                "mapped" => false,
            ])
            ->add('adress', TextType::class, [
                "mapped" => false,
            ])
            ->add('city', TextType::class, [
                "mapped" => false,
            ])
            ->add('zipCode', TextType::class, [
                "mapped" => false,
            ])
            ->add('country', TextType::class, [
                "mapped" => false,
            ])
            ->add('companyName', TextType::class, [
                "mapped" => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
