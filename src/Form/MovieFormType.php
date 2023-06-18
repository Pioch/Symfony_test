<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => array(
                    'class' => 'bg-transparent block border-b-2 w-full h-20 text-6xl outlinenone',
                    'placeholder' => 'Wprowadź tytuł'
                ),
                'label' => false,
                'required' => false
            ])
            ->add('releaseYear', IntegerType::class, [
                'attr' => array(
                    'class' => 'bg-transparent block mt-10 border-b-2 w-full h-20 text-6xl outlinenone',
                    'placeholder' => 'Wprowadź rok wydania'
                ),
                'label' => false,
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'attr' => array(
                    'class' => 'bg-transparent block border-b-2 w-full h-60 text-6xl outlinenone',
                    'placeholder' => 'Wprowadź opis'
                ),
                'label' => false,
                'required' => false
            ])
            ->add('imagePath', FileType::class, array(
                'required' => false, //false, ponieważ nie jest wymagane przy edycji wpisu
                'mapped' => false //czy ma być powiązane z właściwościami entity
                //"data_class" => null //brak wybranego pliku przy edycji
            ))
            //->add('actors')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
