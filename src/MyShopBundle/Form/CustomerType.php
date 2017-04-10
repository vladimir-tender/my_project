<?php

namespace MyShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['placeholder' => 'Your name'],
                'label' => false,
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'Email'],
                'label' => false
            ])
            ->add('password',RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => false, 'attr' => ['placeholder' => 'Password']],
                'second_options' => ['label' => false, 'attr' => ['placeholder' => 'Repeat Password']],
                'invalid_message' => 'Different passwords'
            ])

            ->add('address', TextType::class, [
                'attr' => ['placeholder' => 'Adress'],
                'label' => false,
                'required' => false
            ])
            ->add('city', TextType::class, [
                'attr' => ['placeholder' => 'City'],
                'label' => false,
                'required' => false
            ])
            ->add('phone', TextType::class, [
                'attr' => ['placeholder' => 'Phone'],
                'label' => false,
                'required' => false
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyShopBundle\Entity\Customer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myshopbundle_customer';
    }


}
