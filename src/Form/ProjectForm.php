<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Name',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', TextType::class, [
                'required' => false,
                'label' => 'Description',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Created At',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('updatedAt', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Updated At',
                'attr' => ['class' => 'form-control'],
            ]);
    }
}
