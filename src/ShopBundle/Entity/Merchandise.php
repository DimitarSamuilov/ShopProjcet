<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Merchandise
 *
 * @ORM\Table(name="merchandise")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\MerchandiseRepository")
 */
class Merchandise
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=false)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var mixed
     *
     * @Assert\Image(
     *
     *     mimeTypes="image/*"
     *
     * )
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\User",inversedBy="merchandise")
     * @ORM\JoinTable(name="userId")
     *
     * @var User
     */
    private $user;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Category",inversedBy="merchandise")
     * @ORM\JoinTable(name="categoryId")
     */
    private $category;

    /**
     * @var string
     */
    private $summary;

    /**
     * @var double
     *
     * @ORM\Column(name="promo_price", type="float")
     */
    private $promoPrice;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_added",type="datetime")
     */
    private $dateAdded;

    /**
     * Merchandise constructor.
     */
    public function __construct()
    {
        $this->dateAdded=new \DateTime();
/*        $this->setImage('\Resources\Images\product-img.png');*/
    }

    /**
     * @return mixed
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param mixed $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    public function getFormattedDateAdded()
    {
        return $this->getDateAdded()->format('Y-m-d H:i:s');
    }
    /**
     * @return string
     */
    public function getSummary(): string
    {
        if ($this->summary == null) {
            $this->setSummary();
        }
        return $this->summary;
    }

    /**
     * @return float
     */
    public function getPromoPrice()
    {
        return $this->promoPrice;
    }

    /**
     * @param float $promoPrice
     */
    public function setPromoPrice($promoPrice)
    {
        $this->promoPrice = $promoPrice;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }


    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Merchandise
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Merchandise
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Merchandise
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}

