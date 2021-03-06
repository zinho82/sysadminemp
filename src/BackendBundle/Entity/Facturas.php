<?php

namespace BackendBundle\Entity;

/**
 * Facturas
 */
class Facturas
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var \DateTime
     */
    private $fechaIngreso;

    /**
     * @var \DateTime
     */
    private $fechaPago;

    /**
     * @var float
     */
    private $numeroFactura;

    /**
     * @var float
     */
    private $neto;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $itemGasto;

    /**
     * @var \BackendBundle\Entity\Campana
     */
    private $campana;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $estadoPago;

    /**
     * @var \BackendBundle\Entity\Departamentos
     */
    private $departamento;

    /**
     * @var \BackendBundle\Entity\Empresa
     */
    private $empresa;

    /**
     * @var \BackendBundle\Entity\Ordenescompra
     */
    private $ordenescompra;

    /**
     * @var \BackendBundle\Entity\ProveedoresClientes
     */
    private $proveedoresClientes;


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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Facturas
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return Facturas
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
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     *
     * @return Facturas
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
     * Set numeroFactura
     *
     * @param float $numeroFactura
     *
     * @return Facturas
     */
    public function setNumeroFactura($numeroFactura)
    {
        $this->numeroFactura = $numeroFactura;

        return $this;
    }

    /**
     * Get numeroFactura
     *
     * @return float
     */
    public function getNumeroFactura()
    {
        return $this->numeroFactura;
    }

    /**
     * Set neto
     *
     * @param float $neto
     *
     * @return Facturas
     */
    public function setNeto($neto)
    {
        $this->neto = $neto;

        return $this;
    }

    /**
     * Get neto
     *
     * @return float
     */
    public function getNeto()
    {
        return $this->neto;
    }

    /**
     * Set itemGasto
     *
     * @param \BackendBundle\Entity\Config $itemGasto
     *
     * @return Facturas
     */
    public function setItemGasto(\BackendBundle\Entity\Config $itemGasto = null)
    {
        $this->itemGasto = $itemGasto;

        return $this;
    }

    /**
     * Get itemGasto
     *
     * @return \BackendBundle\Entity\Config
     */
    public function getItemGasto()
    {
        return $this->itemGasto;
    }

    /**
     * Set campana
     *
     * @param \BackendBundle\Entity\Campana $campana
     *
     * @return Facturas
     */
    public function setCampana(\BackendBundle\Entity\Campana $campana = null)
    {
        $this->campana = $campana;

        return $this;
    }

    /**
     * Get campana
     *
     * @return \BackendBundle\Entity\Campana
     */
    public function getCampana()
    {
        return $this->campana;
    }

    /**
     * Set estadoPago
     *
     * @param \BackendBundle\Entity\Config $estadoPago
     *
     * @return Facturas
     */
    public function setEstadoPago(\BackendBundle\Entity\Config $estadoPago = null)
    {
        $this->estadoPago = $estadoPago;

        return $this;
    }

    /**
     * Get estadoPago
     *
     * @return \BackendBundle\Entity\Config
     */
    public function getEstadoPago()
    {
        return $this->estadoPago;
    }

    /**
     * Set departamento
     *
     * @param \BackendBundle\Entity\Departamentos $departamento
     *
     * @return Facturas
     */
    public function setDepartamento(\BackendBundle\Entity\Departamentos $departamento = null)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento
     *
     * @return \BackendBundle\Entity\Departamentos
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Set empresa
     *
     * @param \BackendBundle\Entity\Empresa $empresa
     *
     * @return Facturas
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
     * Set ordenescompra
     *
     * @param \BackendBundle\Entity\Ordenescompra $ordenescompra
     *
     * @return Facturas
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
     * Set proveedoresClientes
     *
     * @param \BackendBundle\Entity\ProveedoresClientes $proveedoresClientes
     *
     * @return Facturas
     */
    public function setProveedoresClientes(\BackendBundle\Entity\ProveedoresClientes $proveedoresClientes = null)
    {
        $this->proveedoresClientes = $proveedoresClientes;

        return $this;
    }

    /**
     * Get proveedoresClientes
     *
     * @return \BackendBundle\Entity\ProveedoresClientes
     */
    public function getProveedoresClientes()
    {
        return $this->proveedoresClientes;
    }
}
