<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\LayoutTemplate;
use App\Entity\Project;
use App\Entity\Section;
use App\Repository\ImageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Project|null $project */
        $project = $options['project'];

        $builder
            ->add('template', EntityType::class, [
                'class' => LayoutTemplate::class,
                'choice_label' => 'name',
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
            ])
            ->add('images', EntityType::class, [
                'class' => Image::class,
                'choice_label' => 'filename',
                'multiple' => true,
                'required' => false,
                'query_builder' => function (ImageRepository $repo) use ($project) {
                    return $repo->createQueryBuilder('i')
                        ->andWhere('i.project = :project')
                        ->setParameter('project', $project);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
            'project' => null,
        ]);
        $resolver->setAllowedTypes('project', ['null', Project::class]);
    }
}
