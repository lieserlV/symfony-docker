<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label mt-2']
                ])
            ->add('texte', TextType:: class, [
            'attr' => ['class' => 'form-control'],
            'label_attr' => ['class' => 'form-label mt-2']
             ])
            ->add('publie', CheckboxType::class, [
                'attr' => ['class' => 'form-check-input m-2'],
                'label_attr' => ['class' => 'form-check-label mt-1']
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label mt-2']
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }

    
}
