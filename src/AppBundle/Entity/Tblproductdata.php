<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tblproductdata
 *
 * @ORM\Table(name="tblproductdata")
 */
class Tblproductdata
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @var string
     */
    private $strproductname;

    /**
     * @var string
     */
    private $strproductdesc;

    /**
     * @var string
     */
    private $strproductcode;

    /**
     * @var \DateTime
     */
    private $dtmadded;

    /**
     * @var \DateTime
     */
    private $dtmdiscontinued;

    /**
     * @var \DateTime
     */
    private $stmtimestamp = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     */
    private $intproductdataid;

    /**
     * @var integer
     */
    private $stocklevel;

    /**
     * @var float
     */
    private $price;

    /**
     * Set stocklevel
     *
     * @param integer $stocklevel
     *
     * @return Tblproductdata
     */
    public function setStocklevel($stocklevel)
    {
        $this->stocklevel = $stocklevel;

        return $this;
    }

    /**
     * Get stocklevel
     *
     * @return integer
     */
    public function getStocklevel()
    {
        return $this->stocklevel;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Tblproductdata
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
     * Set strproductname
     *
     * @param string $strproductname
     *
     * @return Tblproductdata
     */
    public function setStrproductname($strproductname)
    {
        $this->strproductname = $strproductname;

        return $this;
    }

    /**
     * Get strproductname
     *
     * @return string
     */
    public function getStrproductname()
    {
        return $this->strproductname;
    }

    /**
     * Set strproductdesc
     *
     * @param string $strproductdesc
     *
     * @return Tblproductdata
     */
    public function setStrproductdesc($strproductdesc)
    {
        $this->strproductdesc = $strproductdesc;

        return $this;
    }

    /**
     * Get strproductdesc
     *
     * @return string
     */
    public function getStrproductdesc()
    {
        return $this->strproductdesc;
    }

    /**
     * Set strproductcode
     *
     * @param string $strproductcode
     *
     * @return Tblproductdata
     */
    public function setStrproductcode($strproductcode)
    {
        $this->strproductcode = $strproductcode;

        return $this;
    }

    /**
     * Get strproductcode
     *
     * @return string
     */
    public function getStrproductcode()
    {
        return $this->strproductcode;
    }

    /**
     * Set dtmadded
     *
     * @param \DateTime $dtmadded
     *
     * @return Tblproductdata
     */
    public function setDtmadded($dtmadded)
    {
        $this->dtmadded = $dtmadded;

        return $this;
    }

    /**
     * Get dtmadded
     *
     * @return \DateTime
     */
    public function getDtmadded()
    {
        return $this->dtmadded;
    }

    /**
     * Set dtmdiscontinued
     *
     * @param \DateTime $dtmdiscontinued
     *
     * @return Tblproductdata
     */
    public function setDtmdiscontinued($dtmdiscontinued)
    {
        $this->dtmdiscontinued = $dtmdiscontinued;

        return $this;
    }

    /**
     * Get dtmdiscontinued
     *
     * @return \DateTime
     */
    public function getDtmdiscontinued()
    {
        return $this->dtmdiscontinued;
    }

    /**
     * Set stmtimestamp
     *
     * @param \DateTime $stmtimestamp
     *
     * @return Tblproductdata
     */
    public function setStmtimestamp($stmtimestamp)
    {
        $this->stmtimestamp = $stmtimestamp;

        return $this;
    }

    /**
     * Get stmtimestamp
     *
     * @return \DateTime
     */
    public function getStmtimestamp()
    {
        return $this->stmtimestamp;
    }

    /**
     * Get intproductdataid
     *
     * @return integer
     */
    public function getIntproductdataid()
    {
        return $this->intproductdataid;
    }
}
