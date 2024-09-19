<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'help' => User::PASSWORD_PATTERN_HELP,
                'help_attr' => ['class' => 'text-xs font-weight-normal'],
                'options' => ['attr' => ['class' => 'form-control']],
                'constraints' => [new Regex(
                    [
                        'pattern' => User::PASSWORD_PATTERN,
                        'message' => User::PASSWORD_PATTERN_MESSAGE
                    ]
                )],
                'invalid_message' => 'Les mots de passe ne sont pas identiques',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
