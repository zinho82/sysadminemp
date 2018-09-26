<?php

namespace BackendBundle\Entity;

/**
 * Autorizaciones
 */
class Autorizaciones
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $fechaAutorizacion;

    /**
     * @var string
     */
    private $comentario;

    /**
     * @var \BackendBundle\Entity\Usuario
     */
    private $autorizadoPor;

    /**
     * @var \BackendBundle\Entity\Solicitudes
     */
    private $solicitud;


    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Autorizaciones
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set fechaAutorizacion
     *
     * @param \DateTime $fechaAutorizacion
     *
     * @return Autorizaciones
     */
    public function setFechaAutorizacion($fechaAutorizacion)
    {
        $this->fechaAutorizacion = $fechaAutorizacion;

        return $this;
    }

    /**
     * Get fechaAutorizacion
     *
     * @return \DateTime
     */
    public function getFechaAutorizacion()
    {
        return $this->fechaAutorizacion;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     *
     * @return Autorizaciones
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
     * Set autorizadoPor
     *
     * @param \BackendBundle\Entity\Usuario $autorizadoPor
     *
     * @return Autorizaciones
     */
    public function setAutorizadoPor(\BackendBundle\Entity\Usuario $autorizadoPor = null)
    {
        $this->autorizadoPor = $autorizadoPor;

        return $this;
    }

    /**
     * Get autorizadoPor
     *
     * @return \BackendBundle\Entity\Usuario
     */
    public function getAutorizadoPor()
    {
        return $this->autorizadoPor;
    }

    /**
     * Set solicitud
     *
     * @param \BackendBundle\Entity\Solicitudes $solicitud
     *
     * @return Autorizaciones
     */
    public function setSolicitud(\BackendBundle\Entity\Solicitudes $solicitud = null)
    {
        $this->solicitud = $solicitud;

        return $this;
    }

    /**
     * Get solicitud
     *
     * @return \BackendBundle\Entity\Solicitudes
     */
    public function getSolicitud()
    {
        return $this->solicitud;
    }
}

