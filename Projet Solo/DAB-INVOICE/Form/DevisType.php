<?php

namespace App\Form;

use App\Entity\Devis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('nom', TextType::class, [
                'label' => 'Nom de devis',
                'required' => true,
             ])
             ->add('montant', MoneyType::class,[
                'label' => 'Montant',
                'required' => true,
             ])
             ->add('dateEmission', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'émission',
             ])
             ->add('statut', ChoiceType::class, [
                'choices'  => [
                    'À traiter' => 'a_traiter',
                    'En attente' => 'en_attente',
                    'Finalisé' => 'finalise',
                ],
                'label' => 'Statut du devis',
             ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
