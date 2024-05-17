<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Like;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Enum\UserTypes;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('nom', TextType::class, [
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('prenom', TextType::class, [
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('email', EmailType::class, [
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Email(),
            ],
        ])
        ->add('numtel', TelType::class, [
            'constraints' => [
                new Assert\NotBlank(),
                // Add any other constraints as needed
            ],
        ])
        ->add('type', ChoiceType::class, [
            'choices' => [
                'Etudiant' => UserTypes::ETUDIANT,
                'Enseignant' => UserTypes::ENSEIGNANT,
                'Administrateur' => UserTypes::ADMIN,
            ],
            'placeholder' => 'Choose a type',
            'constraints' => [
                new Assert\NotBlank(),
                // Add any other constraints as needed
            ],
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
            'constraints' => [
                new Assert\NotBlank(),
                // Add any other constraints as needed
            ],
        ]);

    $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        if (!$data instanceof User) {
            return;
        }

        $userType = $data->getType();

        if ($userType === UserTypes::ETUDIANT) {
            $form->add('etudiant', EtudiantType::class);
        } elseif ($userType === UserTypes::ENSEIGNANT) {
            $form->add('enseignant', EnseignantType::class);
        } elseif ($userType === UserTypes::ADMIN) {
            $form->add('administrateur', AdministrateurType::class);
        }
    });
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
