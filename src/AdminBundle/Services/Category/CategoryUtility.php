<?php

namespace AdminBundle\Services\Category;


use Doctrine\ORM\EntityManager;

class CategoryUtility
{

    private $categoryRepository;

    /**
     * CategoryUtility constructor.
     * @param EntityManager $doctrine
     */
    public function __construct($doctrine)
    {
        $this->categoryRepository = $doctrine->getRepository("MyShopBundle:Category");
    }


    public function getCategoryTree()
    {//admin.product.categorylist
        $categoryTree = [];
        $parentCategoriesList = $this->categoryRepository->findBy(['idparent' => null], ['category' => 'ASC']);

        foreach ($parentCategoriesList as $parentCategory) {
            $categoryTree[] = $parentCategory;
            $subCategoryList = $this->categoryRepository->findBy(['idparent' => $parentCategory->getId()], ['category' => 'ASC']);

            foreach ($subCategoryList as $subCategory) {
                $categoryTree[] = $subCategory;
            }
        }

        return $categoryTree;
    }

    public function getCategoryListTree()
    {//admin.category.list
        $categoryTree = [];
        $parentCategoriesList = $this->categoryRepository->findBy(['idparent' => null], ['category' => 'ASC']);

        foreach ($parentCategoriesList as $parentCategory) {
            $categoryTree[] = $parentCategory;
            $subCategoryList = $this->categoryRepository->findBy(['idparent' => $parentCategory->getId()], ['category' => 'ASC']);

            foreach ($subCategoryList as $subCategory) {
                $categoryTree[$parentCategory->getId()][] = $subCategory;
            }
        }

        return $categoryTree;
    }

    /**
     * @return array
     */
    public function getCategoryChoicesArray()
    {//unused
        $categoryChoicesArray = [];
        $parentCategoriesList = $this->categoryRepository->findBy(['idparent' => null], ['category' => 'ASC']);

        foreach ($parentCategoriesList as $parentCategory) {
            //$categoryChoicesArray[$parentCategory->getCategory()][] = $parentCategory->getId();
            $subCategoryList = $this->categoryRepository->findBy(['idparent' => $parentCategory->getId()], ['category' => 'ASC']);

            foreach ($subCategoryList as $subCategory) {
                $categoryChoicesArray[$parentCategory->getCategory()][$subCategory->getCategory()] = $subCategory->getId();
            }
        }

        //var_dump($categoryChoicesArray);
        return $categoryChoicesArray;
    }

    public function getCategoryParentChoicesArray()
    {//Unused
        $parentCategoriesList = $this->categoryRepository->findBy(['idparent' => null], ['category' => 'ASC']);
        $parentCategories = [];

        foreach ($parentCategoriesList as $parentCategory) {
            $parentCategories[$parentCategory->getCategory()] = $parentCategory->getId();
        }
        //var_dump($parentCategoriesList);
        return $parentCategories;
    }

    public function getCategoriesForTreeJson()
    {
        $categories = $this->categoryRepository->findAll();

        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                "id" => $category->getId(),
                "parent" => $parent = is_null($category->getIdParent()) ? "#" : $category->getIdParent()->getId(),
                "text" => $category->getCategory(),
            ];
        }

        return json_encode($data);
    }
}