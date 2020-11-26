<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserType extends AbstractType
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $encoder = $this->encoder;
        $builder
            ->add('email', EmailType::class)
            // ->add('roles', ChoiceType::class)
            ->add('password', PasswordType::class)
            // Nom de la variable de l entite cible
            ->add('userRoles', EntityType::class, [
                'class' => Role::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($encoder) {
                /** @var User */
                $user = $event->getData();
                $form = $event->getForm();
                if ($user) {
                    $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

                    /** @var Role */
                    foreach ($user->getUserRoles()->toArray() as $role) {
                        $role->addUser($user);
                    }
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
