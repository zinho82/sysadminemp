<?php

namespace BackendBundle\Entity;

/**
 * MovimientosBanco
 */
class MovimientosBanco
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $detalle;

    /**
     * @var \BackendBundle\Entity\Banco
     */
    private $banco;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $tipoMoviemiento;

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
     * Set detalle
     *
     * @param string $detalle
     *
     * @return MovimientosBanco
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set banco
     *
     * @param \BackendBundle\Entity\Banco $banco
     *
     * @return MovimientosBanco
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

    /**
     * Set tipoMoviemiento
     *
     * @param \BackendBundle\Entity\Config $tipoMoviemiento
     *
     * @return MovimientosBanco
     */
    public function setTipoMoviemiento(\BackendBundle\Entity\Config $tipoMoviemiento = null)
    {
        $this->tipoMoviemiento = $tipoMoviemiento;

        return $this;
    }

    /**
     * Get tipoMoviemiento
     *
     * @return \BackendBundle\Entity\Config
     */
    public function getTipoMoviemiento()
    {
        return $this->tipoMoviemiento;
    }

    /**
     * Set empresa
     *
     * @param \BackendBundle\Entity\Empresa $empresa
     *
     * @return MovimientosBanco
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
