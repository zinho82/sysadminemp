<?php

namespace BackendBundle\Entity;

/**
 * Inventario
 */
class Inventario
{
    /**
     * @var integer
     */
    private $id;

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
     * Set empresa
     *
     * @param \BackendBundle\Entity\Empresa $empresa
     *
     * @return Inventario
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
     * @var string
     */
    private $nombreProducto;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var float
     */
    private $cantidad;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $estado;


    /**
     * Set nombreProducto
     *
     * @param string $nombreProducto
     *
     * @return Inventario
     */
    public function setNombreProducto($nombreProducto)
    {
        $this->nombreProducto = $nombreProducto;

        return $this;
    }

    /**
     * Get nombreProducto
     *
     * @return string
     */
    public function getNombreProducto()
    {
        return $this->nombreProducto;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Inventario
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     *
     * @return Inventario
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set estado
     *
     * @param \BackendBundle\Entity\Config $estado
     *
     * @return Inventario
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
