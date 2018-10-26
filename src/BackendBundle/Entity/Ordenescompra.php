<?php

namespace BackendBundle\Entity;

/**
 * Ordenescompra
 */
class Ordenescompra
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $numeroOc;

    /**
     * @var \DateTime
     */
    private $fechaEstimadaCompra;

    /**
     * @var \DateTime
     */
    private $fechaIngreso;

    /**
     * @var float
     */
    private $total;

    /**
     * @var float
     */
    private $iva;

    /**
     * @var float
     */
    private $subtotal;

    /**
     * @var \BackendBundle\Entity\Campana
     */
    private $campana;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $tipoOc;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $estado;

    /**
     * @var \BackendBundle\Entity\Empresa
     */
    private $empresa;

    /**
     * @var \BackendBundle\Entity\ProveedoresClientes
     */
    private $proveedoresClientes;

    /**
     * @var \BackendBundle\Entity\Usuario
     */
    private $solicitadoPor;


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
     * Set numeroOc
     *
     * @param float $numeroOc
     *
     * @return Ordenescompra
     */
    public function setNumeroOc($numeroOc)
    {
        $this->numeroOc = $numeroOc;

        return $this;
    }

    /**
     * Get numeroOc
     *
     * @return float
     */
    public function getNumeroOc()
    {
        return $this->numeroOc;
    }

    /**
     * Set fechaEstimadaCompra
     *
     * @param \DateTime $fechaEstimadaCompra
     *
     * @return Ordenescompra
     */
    public function setFechaEstimadaCompra($fechaEstimadaCompra)
    {
        $this->fechaEstimadaCompra = $fechaEstimadaCompra;

        return $this;
    }

    /**
     * Get fechaEstimadaCompra
     *
     * @return \DateTime
     */
    public function getFechaEstimadaCompra()
    {
        return $this->fechaEstimadaCompra;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return Ordenescompra
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
     * Set total
     *
     * @param float $total
     *
     * @return Ordenescompra
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
     * Set iva
     *
     * @param float $iva
     *
     * @return Ordenescompra
     */
    public function setIva($iva)
    {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Get iva
     *
     * @return float
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set subtotal
     *
     * @param float $subtotal
     *
     * @return Ordenescompra
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get subtotal
     *
     * @return float
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set campana
     *
     * @param \BackendBundle\Entity\Campana $campana
     *
     * @return Ordenescompra
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
     * Set tipoOc
     *
     * @param \BackendBundle\Entity\Config $tipoOc
     *
     * @return Ordenescompra
     */
    public function setTipoOc(\BackendBundle\Entity\Config $tipoOc = null)
    {
        $this->tipoOc = $tipoOc;

        return $this;
    }

    /**
     * Get tipoOc
     *
     * @return \BackendBundle\Entity\Config
     */
    public function getTipoOc()
    {
        return $this->tipoOc;
    }

    /**
     * Set estado
     *
     * @param \BackendBundle\Entity\Config $estado
     *
     * @return Ordenescompra
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

    /**
     * Set empresa
     *
     * @param \BackendBundle\Entity\Empresa $empresa
     *
     * @return Ordenescompra
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
     * Set proveedoresClientes
     *
     * @param \BackendBundle\Entity\ProveedoresClientes $proveedoresClientes
     *
     * @return Ordenescompra
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

    /**
     * Set solicitadoPor
     *
     * @param \BackendBundle\Entity\Usuario $solicitadoPor
     *
     * @return Ordenescompra
     */
    public function setSolicitadoPor(\BackendBundle\Entity\Usuario $solicitadoPor = null)
    {
        $this->solicitadoPor = $solicitadoPor;

        return $this;
    }

    /**
     * Get solicitadoPor
     *
     * @return \BackendBundle\Entity\Usuario
     */
    public function getSolicitadoPor()
    {
        return $this->solicitadoPor;
    }
    public function __toString() {
        return (string) $this->numeroOc;
    }
}

