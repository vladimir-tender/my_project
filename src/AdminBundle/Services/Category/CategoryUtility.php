<?php

namespace AdminBundle\Services\Category;

class CategoryUtility
{

    private $repository;

    public function setRepository()
    {

    }

    public function __construct()
    {

    }


    public function getCategoryTree($categoryRepository)
    {
        $categoryTree = [];
        $parentCategoriesList = $categoryRepository->findBy(['idparent' => null], ['category' => 'ASC']);

        foreach ($parentCategoriesList as $parentCategory)
        {
            $categoryTree[] = $parentCategory;
            $subCategoryList = $categoryRepository->findBy(['idparent' => $parentCategory->getId()], ['category' => 'ASC']);

            foreach ($subCategoryList as $subCategory)
            {
                $categoryTree[] = $subCategory;
            }
        }

        return $categoryTree;
    }

    public function getCategoryListTree($categoryRepository)
    {
        $categoryTree = [];
        $parentCategoriesList = $categoryRepository->findBy(['idparent' => null], ['category' => 'ASC']);

        foreach ($parentCategoriesList as $parentCategory)
        {
            $categoryTree[] = $parentCategory;
            $subCategoryList = $categoryRepository->findBy(['idparent' => $parentCategory->getId()], ['category' => 'ASC']);

            foreach ($subCategoryList as $subCategory)
            {
                $categoryTree[$parentCategory->getId()][] = $subCategory;
            }
        }

        return $categoryTree;
    }

    public function getCategoryChoicesArray($categoryRepository)
    {
        $categoryChoicesArray = [];
        $parentCategoriesList = $categoryRepository->findBy(['idparent' => null], ['category' => 'ASC']);

        foreach ($parentCategoriesList as $parentCategory)
        {
            //$categoryChoicesArray[$parentCategory->getCategory()][] = $parentCategory->getId();
            $subCategoryList = $categoryRepository->findBy(['idparent' => $parentCategory->getId()], ['category' => 'ASC']);

            foreach ($subCategoryList as $subCategory)
            {
                $categoryChoicesArray[$parentCategory->getCategory()][$subCategory->getCategory()] = $subCategory->getId();
            }
        }

        //var_dump($categoryChoicesArray);
        return $categoryChoicesArray;
    }

    public function getCategoryParentChoicesArray($categoryRepository)
    {
        $parentCategoriesList = $categoryRepository->findBy(['idparent' => null], ['category' => 'ASC']);
        $parentCategories = [];

        foreach ($parentCategoriesList as $parentCategory) {
            $parentCategories[$parentCategory->getCategory()] = $parentCategory->getId();
        }
        //var_dump($parentCategoriesList);
        return $parentCategories;
    }
}