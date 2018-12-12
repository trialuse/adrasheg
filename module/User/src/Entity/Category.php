<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="category")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="image")
     */
    protected $image;
    
    /** 
     * @ORM\Column(name="is_main")
     */
    protected $is_main;

    /**
     * @ORM\Column(name="category")
     */
    protected $category;
    
    /**
     * Returns user ID.
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Sets user ID. 
     * @param int $id    
     */
    public function setId($id) 
    {
        $this->id = $id;
    }

    /**
     * Returns image.
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets image.
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
    
    /**
     * Returns is_main.
     * @return string     
     */
    public function getIsMain()
    {
        return $this->is_main;
    }       

    /**
     * Sets is_main.
     * @param string $is_main
     */
    public function setIsMain($is_main)
    {
        $this->is_main = $is_main;
    }

    /**
     * Returns category.
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets category.
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
}



