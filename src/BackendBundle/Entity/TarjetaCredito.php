<?php

namespace BackendBundle\Entity;

/**
 * TarjetaCredito
 */
class TarjetaCredito
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $numeroTarjetaCredito;

    /**
     * @var \BackendBundle\Entity\Banco
     */
    private $banco;


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
     * Set numeroTarjetaCredito
     *
     * @param string $numeroTarjetaCredito
     *
     * @return TarjetaCredito
     */
    public function setNumeroTarjetaCredito($numeroTarjetaCredito)
    {
        $this->numeroTarjetaCredito = $numeroTarjetaCredito;

        return $this;
    }

    /**
     * Get numeroTarjetaCredito
     *
     * @return string
     */
    public function getNumeroTarjetaCredito()
    {
        return $this->numeroTarjetaCredito;
    }

    /**
     * Set banco
     *
     * @param \BackendBundle\Entity\Banco $banco
     *
     * @return TarjetaCredito
     */
    public function setBanco(\BackendBundle\Entity\Banco $banco = null)
    {
        $this->banco = $banco;

        return $this;
    }

    /**
     * Get banco
     *
     * @return \BackendBundle\Entity\Banco
     */
    public function getBanco()
    {
        return $this->banco;
    }
}

