<?php

namespace BackendBundle\Entity;

/**
 * Cheques
 */
class Cheques
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $numeroCheque;

    /**
     * @var \DateTime
     */
    private $fechaCobro;

    /**
     * @var \BackendBundle\Entity\Registropago
     */
    private $registropago;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $banco;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $estado;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numeroCheque
     *
     * @param string $numeroCheque
     *
     * @return Cheques
     */
    public function setNumeroCheque($numeroCheque)
    {
        $this->numeroCheque = $numeroCheque;

        return $this;
    }

    /**
     * Get numeroCheque
     *
     * @return string
     */
    public function getNumeroCheque()
    {
        return $this->numeroCheque;
    }

    /**
     * Set fechaCobro
     *
     * @param \DateTime $fechaCobro
     *
     * @return Cheques
     */
    public function setFechaCobro($fechaCobro)
    {
        $this->fechaCobro = $fechaCobro;

        return $this;
    }

    /**
     * Get fechaCobro
     *
     * @return \DateTime
     */
    public function getFechaCobro()
    {
        return $this->fechaCobro;
    }

    /**
     * Set registropago
     *
     * @param \BackendBundle\Entity\Registropago $registropago
     *
     * @return Cheques
     */
    public function setRegistropago(\BackendBundle\Entity\Registropago $registropago = null)
    {
        $this->registropago = $registropago;

        return $this;
    }

    /**
     * Get registropago
     *
     * @return \BackendBundle\Entity\Registropago
     */
    public function getRegistropago()
    {
        return $this->registropago;
    }

    /**
     * Set banco
     *
     * @param \BackendBundle\Entity\Config $banco
     *
     * @return Cheques
     */
    public function setBanco(\BackendBundle\Entity\Config $banco = null)
    {
        $this->banco = $banco;

        return $this;
    }

    /**
     * Get banco
     *
     * @return \BackendBundle\Entity\Config
     */
    public function getBanco()
    {
        return $this->banco;
    }

    /**
     * Set estado
     *
     * @param \BackendBundle\Entity\Config $estado
     *
     * @return Cheques
     */
    public function setEstado(\BackendBundle\Entity\Config $estado = null)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \BackendBundle\Entity\Config
     */
    public function getEstado()
    {
        return $this->estado;
    }
}

