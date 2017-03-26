<?php

namespace MyShopBundle\Form;

use Doctrine\ORM\EntityRepository;
use MyShopBundle\Entity\Category;
use MyShopBundle\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => "MyShopBundle:Category",
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.idparent IS NOT NULL')->orderBy('c.category', 'ASC');
                },
                "choice_label" => "category",
                "label" => "Категория",
                'group_by' => function ($val, $key, $index) {
                    /**
                     * @var Category $val
                     */
                    if ($val->getIdparent() != null) {
                        return $val->getIdparent()->getCategory();
                    } else {
                        return 'this option mustnt return';
                    }
                }
            ])
            ->add('productname', TextType::class, [
                "label" => "Модель товара",
            ])
            ->add('price', NumberType::class, [
                "label" => 'Цена товара'
            ])
            ->add('offer', NumberType::class, [
                "label" => 'Акция'
            ])
            ->add('discount', NumberType::class, [
                "label" => 'Скидка'
            ])
            ->add('description', TextareaType::class, [
                'label' => "Описание товара"
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyShopBundle\Entity\Product',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myshopbundle_product';
    }


}
