<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, [
                'label'    => 'Comment vous appelez-vous ?',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label'    => 'Email',
                'required' => true,
            ])
            ->add('subject', TextType::class, [
                'label'    => 'Sujet',
                'required' => true,
            ])
            ->add('message', TextareaType::class, [
                'label'    => 'Votre message',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer mon message !',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
