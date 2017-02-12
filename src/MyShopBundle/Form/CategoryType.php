<?php

namespace MyShopBundle\Form;

use Doctrine\ORM\EntityRepository;
use MyShopBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('category', TextType::class, [
                "label" => "Название",
            ])
            ->add('idparent', EntityType::class, [
                'class' => 'MyShopBundle:Category',
                'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('c')
                    ->where('c.idparent IS NULL')->orderBy('c.category', 'ASC');},
                'required' => false,
                'choice_label' => 'category',
                'label' => 'Родительская категория'
            ]);
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyShopBundle\Entity\Category'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myshopbundle_category';
    }


}
