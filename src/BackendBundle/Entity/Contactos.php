<?php

namespace BackendBundle\Entity;

/**
 * Contactos
 */
class Contactos
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $telefono;

    /**
     * @var string
     */
    private $correo;

    /**
     * @var string
     */
    private $cargo;

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Contactos
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Contactos
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set correo
     *
     * @param string $correo
     *
     * @return Contactos
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set cargo
     *
     * @param string $cargo
     *
     * @return Contactos
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set proveedoresClientes
     *
     * @param \BackendBundle\Entity\ProveedoresClientes $proveedoresClientes
     *
     * @return Contactos
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

