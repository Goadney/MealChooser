<?php

namespace App\Form;

use App\Entity\Repas;
use App\Entity\Saisons;
use App\Entity\Ingredients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RepasModifyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('duree', ChoiceType::class, [
                'choices' => [
                    'Sain' => 'SAIN',
                    'Moyen' => 'MOYEN',
                    'Gras' => 'GRAS',
                    'Entrée' => 'ENTREE',
                ],
                'label' => 'Type de plat',
                'required' => true,
                'expanded' => false, // false = liste déroulante, true = boutons radio
                'multiple' => false, // false = choix unique, true = choix multiple
            ])
            ->add('weekend', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'label' => 'Plat de weekend ?',
                'required' => true,
                'expanded' => true, // Affichera des boutons radio
                'multiple' => false,
            ])
            
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Court' => 'COURT',
                    'Normal' => 'NORMAL',
                    'Long' => 'LONG',
                ],
                'label' => 'Durée de préparation',
                'required' => true,
                'expanded' => false, // false = liste déroulante, true = boutons radio
                'multiple' => false, // false = choix unique, true = choix multiple
            ])
            ->add('ingredients', EntityType::class, [
                'class' => Ingredients::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true, // false = liste déroulante, true = boutons radio

            ])
            ->add('saisons', EntityType::class, [
                'class' => Saisons::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true, // false = liste déroulante, true = boutons radio

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Repas::class,
        ]);
    }
}
