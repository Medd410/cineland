<?php

namespace App\Form;

use App\Entity\Acteur;
use App\Entity\FilmSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'required' => false
            ])
            ->add('anneeMin', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'N/A',
                'choices'  => range(1900, 2030),
                'choice_label' => function ($choice) {
                    return $choice;
                }
            ])
            ->add('anneeMax', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'N/A',
                'choices'  => range(1900,2030),
                'choice_label' => function ($choice) {
                    return $choice;
                }
            ])
            ->add('dateMax', DateType::class, [
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('acteurUn', EntityType::class, [
                'class' => Acteur::class,
                'required' => false
            ])
            ->add('acteurDeux', EntityType::class, [
                'class' => Acteur::class,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FilmSearch::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
