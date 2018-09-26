<?php

namespace BackendBundle\Entity;

/**
 * Config
 */
class Config
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $titulo;

    /**
     * @var integer
     */
    private $pertenece;


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
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Config
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set pertenece
     *
     * @param integer $pertenece
     *
     * @return Config
     */
    public function setPertenece($pertenece)
    {
        $this->pertenece = $pertenece;

        return $this;
    }

    /**
     * Get pertenece
     *
     * @return integer
     */
    public function getPertenece()
    {
        return $this->pertenece;
    }
}
