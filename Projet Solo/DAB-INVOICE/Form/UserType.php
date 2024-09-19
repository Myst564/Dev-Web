<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    final public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => "Prénom",
                'attr' => ['class' => 'form-control', 'placeholder' => "Prénom"]
            ])
            ->add('lastname', TextType::class, [
                'label' => "Nom",
                'attr' => ['class' => 'form-control', 'placeholder' => "Nom"]
            ])
            ->add('email', EmailType::class, [
                'label' => "Email",
                'attr' => ['class' => 'form-control', 'placeholder' => "Email"],
                'constraints' => [
                    new Regex([
                        'pattern' => User::EMAIL_PATTERN,
                        'message' => User::EMAIL_PATTERN_MESSAGE
                    ])
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => "Rôles",
                'multiple' => true,
                'choices' => $this->getChoicesRoles(),
                'attr' => ['class' => 'form-control select2-custom']
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary bg-gradient-primary ms-3 mb-0'
                ]
            ])
        ;
    }

    final public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }

    private function getChoicesRoles(): array
    {
        $choices = User::ROLE_LABEL;
        $output = [];
        foreach ($choices as $key => $value) {
            $output[$value] = $key;
        }

        ksort($output);

        return $output;
    }
}
