<?php
namespace User\Service;

use User\Entity\Category;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class CategoryManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * This method adds a new category.
     */
    public function add($data)
    {
        // Create new User entity.
        $category = new Category();
        $category->setImage($data['image']);
        $category->setIsMain($data['is_main']);
        $category->setCategory($data['category']);

        // Add the entity to the entity manager.
        $this->entityManager->persist($category);
                       
        // Apply changes to database.
        $this->entityManager->flush();
        
        return $category;
    }
    
    /**
     * This method updates data of an existing user.
     */
    public function main($category)
    {
        $category->setIsMain(1);

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * Deletes the given category.
     */
    public function delete($category)
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }
}

