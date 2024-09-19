<?php 

namespace App\Form;

use App\Entity\Facture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', Texttype::class, [
                'label' => 'Nom de la facture',
                'required' => true,
            ])
            ->add('montant', MoneyType::class,[
                'label' => 'Montant',
                'required' => true,
            ])
            ->add('dateEmmision', DateType::class,[
                'widget' => 'single_text',
                'label' => 'Date d\'émission',
            ])
            // Ajout du champ "statut" avec ChoiceType
            ->add('statut', ChoiceType::class, [
                'choices'  => [
                    'À traiter' => 'a_traiter',
                    'En attente' => 'en_attente',
                    'Finalisé' => 'finalise',
                ],
                'label' => 'Statut de la facture',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
