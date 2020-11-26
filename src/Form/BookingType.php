<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\Booking;
use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', CustomerType::class)
            ->add('startDate', TextType::class)
            // ->add('startDate', DateType::class, ['widget' => 'single_text'])
            ->add('endDate', DateType::class, ['widget' => 'single_text'])
            // ->add('createdAt')
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'number',
                'choice_value' => 'id',
                // Permet de retourner le prix JS
                'choice_attr' => function ($choice, $key, $value) {
                    return ['data-price' => $choice->getPrice()];
                }
            ])
            ->add('comment', TextareaType::class, [
                'required' => false
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
