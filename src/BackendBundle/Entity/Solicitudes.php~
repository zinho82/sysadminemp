<?php

namespace BackendBundle\Entity;

/**
 * Solicitudes
 */
class Solicitudes
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $valor;

    /**
     * @var \DateTime
     */
    private $fechaSolicitud;

    /**
     * @var float
     */
    private $numeroSolicitud;

    /**
     * @var \BackendBundle\Entity\CamposFormularios
     */
    private $camposFormularios;

    /**
     * @var \BackendBundle\Entity\Empresa
     */
    private $empresa;

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
     * Set valor
     *
     * @param string $valor
     *
     * @return Solicitudes
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set fechaSolicitud
     *
     * @param \DateTime $fechaSolicitud
     *
     * @return Solicitudes
     */
    public function setFechaSolicitud($fechaSolicitud)
    {
        $this->fechaSolicitud = $fechaSolicitud;

        return $this;
    }

    /**
     * Get fechaSolicitud
     *
     * @return \DateTime
     */
    public function getFechaSolicitud()
    {
        return $this->fechaSolicitud;
    }

    /**
     * Set numeroSolicitud
     *
     * @param float $numeroSolicitud
     *
     * @return Solicitudes
     */
    public function setNumeroSolicitud($numeroSolicitud)
    {
        $this->numeroSolicitud = $numeroSolicitud;

        return $this;
    }

    /**
     * Get numeroSolicitud
     *
     * @return float
     */
    public function getNumeroSolicitud()
    {
        return $this->numeroSolicitud;
    }

    /**
     * Set camposFormularios
     *
     * @param \BackendBundle\Entity\CamposFormularios $camposFormularios
     *
     * @return Solicitudes
     */
    public function setCamposFormularios(\BackendBundle\Entity\CamposFormularios $camposFormularios = null)
    {
        $this->camposFormularios = $camposFormularios;

        return $this;
    }

    /**
     * Get camposFormularios
     *
     * @return \BackendBundle\Entity\CamposFormularios
     */
    public function getCamposFormularios()
    {
        return $this->camposFormularios;
    }

    /**
     * Set empresa
     *
     * @param \BackendBundle\Entity\Empresa $empresa
     *
     * @return Solicitudes
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
     * Set solicitadoPor
     *
     * @param \BackendBundle\Entity\Usuario $solicitadoPor
     *
     * @return Solicitudes
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
}

