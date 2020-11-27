<?php

namespace App\Form;

use DateTime;
use App\Entity\Room;
use App\Entity\Booking;
use App\Entity\Customer;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use App\Form\DataTransformer\DateTransformer;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class BookingType extends AbstractType
{
    private $dateTransformer;

    public function __construct(DateTransformer $dateTransformer)
    {
        $this->dateTransformer = $dateTransformer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', CustomerType::class)
            ->add('startDate', TextType::class, ['attr' => ['disabled' => true]])
            // ->add('startDate', DateType::class, ['widget' => 'single_text'])
            ->add('endDate', TextType::class, ['attr' => ['disabled' => true]])
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
            ->add('save', SubmitType::class)
            // 
        ;

        // On transforme selon le type
        $builder->get('startDate')
            ->addModelTransformer($this->dateTransformer);
        $builder->get('endDate')
            ->addModelTransformer($this->dateTransformer);



        // ->addModelTransformer(new CallbackTransformer(
        //     function ($date) {
        //         if ($date) {
        //             return $date->format('Y-m-d');
        //         }
        //     },
        //     function ($dateString) {
        //         if ($dateString) {
        //             return DateTime::createFromFormat('Y-m-d', $dateString);
        //         }
        //     }

        // ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
