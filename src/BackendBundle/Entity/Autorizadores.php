<?php

namespace BackendBundle\Entity;

/**
 * Autorizadores
 */
class Autorizadores
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \BackendBundle\Entity\Ordenescompra
     */
    private $ordenescompra;

    /**
     * @var \BackendBundle\Entity\Usuario
     */
    private $usuario;


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
     * Set ordenescompra
     *
     * @param \BackendBundle\Entity\Ordenescompra $ordenescompra
     *
     * @return Autorizadores
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
     * Set usuario
     *
     * @param \BackendBundle\Entity\Usuario $usuario
     *
     * @return Autorizadores
     */
    public function setUsuario(\BackendBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \BackendBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}

