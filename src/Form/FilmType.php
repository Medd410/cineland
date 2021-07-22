<?php

namespace App\Form;

use App\Entity\Acteur;
use App\Entity\Film;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class)
            ->add('duree', IntegerType::class)
            ->add('dateSortie', DateType::class, [
                'years' => range(1920, 2025)
            ])
            ->add('note', IntegerType::class)
            ->add('ageMinimal', IntegerType::class)
            ->add('genre', EntityType::class, [
                'class' => Genre::class
            ])
            ->add('acteur', EntityType::class, [
                'class' => Acteur::class,
                'required' => false,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
