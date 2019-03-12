<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $listener = function (FormEvent $event) {

            $user = $event->getData();
            $form = $event->getForm();
            
            if(is_null($user->getId())) {
                $form->add('password', RepeatedType::class, [
                    'constraints' => [
                        new NotBlank()
                    ],
                    'type' => PasswordType::class,
                    'empty_data' => '',
                    'invalid_message' => 'Les mots de passe doivent être identiques',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => [
                        'label' => 'Mot de passe',
                        'empty_data' => ''
                    ],
                    'second_options' => [
                        'label' => 'Confirmer le mot de passe','empty_data' => '',
                    ],
                ]);
            } else {
                $form->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'empty_data' => '',
                    'invalid_message' => 'Les mots de passe doivent être identiques',
                    'options' => ['attr' => [
                        'class' => 'password-field'
                        ]],
                    'required' => true,
                    'first_options'  => [
                        'label' => 'Mot de passe',
                        'empty_data' => '',
                        'attr' => [
                            'placeholder' => 'Laisser vide si inchangé'
                        ]
                    ],
                    'second_options' => [
                        'label' => 'Confirmer le mot de passe',
                        'empty_data' => '',
                        'attr' => [
                            'placeholder' => 'Laisser vide si inchangé'
                        ]
                    ,],
                ]);
            }
        };
            
        $builder
            ->add('username',TextType::class, [
                'empty_data' => '', 
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                $listener
            )   
            ->add('email',EmailType::class, [
                'empty_data' => '', 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
