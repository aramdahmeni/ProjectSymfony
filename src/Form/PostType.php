<?php

namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Post;
use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Content',
            ])
            ->add('files', FileType::class, [
                'label' => 'Upload Files',
                'multiple' => true,
                'required' => false,
                'mapped' => false,
            ])
            ->add('estPublie', CheckboxType::class, [
                'label' => 'Make this post public',
                'required' => false, // It's not required
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom', // Assuming 'username' is the property of User entity you want to display in the dropdown
                'placeholder' => 'Select a user', // Optional placeholder
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
