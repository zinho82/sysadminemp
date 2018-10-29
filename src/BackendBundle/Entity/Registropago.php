<?php

namespace BackendBundle\Entity;

/**
 * Registropago
 */
class Registropago
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $total;

    /**
     * @var \DateTime
     */
    private $fechaPago;

    /**
     * @var string
     */
    private $codigoOperacion;

    /**
     * @var string
     */
    private $numeroTc;

    /**
     * @var \BackendBundle\Entity\Facturas
     */
    private $factura;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $formaPago;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $tipooperacion;


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
     * Set total
     *
     * @param float $total
     *
     * @return Registropago
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     *
     * @return Registropago
     */
    public function setFechaPago($fechaPago)
    {
        $this->fechaPago = $fechaPago;

        return $this;
    }

    /**
     * Get fechaPago
     *
     * @return \DateTime
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * Set codigoOperacion
     *
     * @param string $codigoOperacion
     *
     * @return Registropago
     */
    public function setCodigoOperacion($codigoOperacion)
    {
        $this->codigoOperacion = $codigoOperacion;

        return $this;
    }

    /**
     * Get codigoOperacion
     *
     * @return string
     */
    public function getCodigoOperacion()
    {
        return $this->codigoOperacion;
    }

    /**
     * Set numeroTc
     *
     * @param string $numeroTc
     *
     * @return Registropago
     */
    public function setNumeroTc($numeroTc)
    {
        $this->numeroTc = $numeroTc;

        return $this;
    }

    /**
     * Get numeroTc
     *
     * @return string
     */
    public function getNumeroTc()
    {
        return $this->numeroTc;
    }

    /**
     * Set factura
     *
     * @param \BackendBundle\Entity\Facturas $factura
     *
     * @return Registropago
     */
    public function setFactura(\BackendBundle\Entity\Facturas $factura = null)
    {
        $this->factura = $factura;

        return $this;
    }

    /**
     * Get factura
     *
     * @return \BackendBundle\Entity\Facturas
     */
    public function getFactura()
    {
        return $this->factura;
    }

    /**
     * Set formaPago
     *
     * @param \BackendBundle\Entity\Config $formaPago
     *
     * @return Registropago
     */
    public function setFormaPago(\BackendBundle\Entity\Config $formaPago = null)
    {
        $this->formaPago = $formaPago;

        return $this;
    }

    /**
     * Get formaPago
     *
     * @return \BackendBundle\Entity\Config
     */
    public function getFormaPago()
    {
        return $this->formaPago;
    }

    /**
     * Set tipooperacion
     *
     * @param \BackendBundle\Entity\Config $tipooperacion
     *
     * @return Registropago
     */
    public function setTipooperacion(\BackendBundle\Entity\Config $tipooperacion = null)
    {
        $this->tipooperacion = $tipooperacion;

        return $this;
    }

    /**
     * Get tipooperacion
     *
     * @return \BackendBundle\Entity\Config
     */
    public function getTipooperacion()
    {
        return $this->tipooperacion;
    }
}

