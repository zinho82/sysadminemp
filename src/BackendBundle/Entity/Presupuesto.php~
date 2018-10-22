<?php

namespace BackendBundle\Entity;

/**
 * Presupuesto
 */
class Presupuesto
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $fechaIngreso;

    /**
     * @var float
     */
    private $valor;

    /**
     * @var \DateTime
     */
    private $fechaPago;

    /**
     * @var string
     */
    private $item;

    /**
     * @var string
     */
    private $comentario;

    /**
     * @var \BackendBundle\Entity\Ordenescompra
     */
    private $ordenescompra;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $tipoItem;

    /**
     * @var \BackendBundle\Entity\Empresa
     */
    private $empresa;

    /**
     * @var \BackendBundle\Entity\Facturas
     */
    private $facturas;


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
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return Presupuesto
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return Presupuesto
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     *
     * @return Presupuesto
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
     * Set item
     *
     * @param string $item
     *
     * @return Presupuesto
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     *
     * @return Presupuesto
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set ordenescompra
     *
     * @param \BackendBundle\Entity\Ordenescompra $ordenescompra
     *
     * @return Presupuesto
     */
    public function setOrdenescompra(\BackendBundle\Entity\Ordenescompra $ordenescompra = null)
    {
        $this->ordenescompra = $ordenescompra;

        return $this;
    }

    /**
     * Get ordenescompra
     *
     * @return \BackendBundle\Entity\Ordenescompra
     */
    public function getOrdenescompra()
    {
        return $this->ordenescompra;
    }

    /**
     * Set tipoItem
     *
     * @param \BackendBundle\Entity\Config $tipoItem
     *
     * @return Presupuesto
     */
    public function setTipoItem(\BackendBundle\Entity\Config $tipoItem = null)
    {
        $this->tipoItem = $tipoItem;

        return $this;
    }

    /**
     * Get tipoItem
     *
     * @return \BackendBundle\Entity\Config
     */
    public function getTipoItem()
    {
        return $this->tipoItem;
    }

    /**
     * Set empresa
     *
     * @param \BackendBundle\Entity\Empresa $empresa
     *
     * @return Presupuesto
     */
    public function setEmpresa(\BackendBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \BackendBundle\Entity\Empresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set facturas
     *
     * @param \BackendBundle\Entity\Facturas $facturas
     *
     * @return Presupuesto
     */
    public function setFacturas(\BackendBundle\Entity\Facturas $facturas = null)
    {
        $this->facturas = $facturas;

        return $this;
    }

    /**
     * Get facturas
     *
     * @return \BackendBundle\Entity\Facturas
     */
    public function getFacturas()
    {
        return $this->facturas;
    }
}
