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
     * @var integer
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
     * @var \BackendBundle\Entity\ProveedoresClientes
     */
    private $proveedoresClientes;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $tipoOc;

    /**
     * @var \BackendBundle\Entity\Empresa
     */
    private $empresa;


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
     * @param integer $numeroOc
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
     * @return integer
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
}
