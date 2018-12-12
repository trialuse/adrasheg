<?php
namespace User\Service;

use User\Entity\Booking;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class OrderManager
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
     * This method adds a new.
     */
    public function add($data)
    {
        // Create new entity.
        $order = new Booking();
        $order->setEmail($data['email']);
        $order->setImage($data['image']);

        // Add the entity to the entity manager.
        $this->entityManager->persist($order);
                       
        // Apply changes to database.
        $this->entityManager->flush();
        
        return $order;
    }
}

