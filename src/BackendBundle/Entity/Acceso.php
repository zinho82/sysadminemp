<?php

namespace BackendBundle\Entity;

/**
 * Acceso
 */
class Acceso
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \BackendBundle\Entity\Usuario
     */
    private $usuario;

    /**
     * @var \BackendBundle\Entity\Menu
     */
    private $menu;


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
     * Set usuario
     *
     * @param \BackendBundle\Entity\Usuario $usuario
     *
     * @return Acceso
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

    /**
     * Set menu
     *
     * @param \BackendBundle\Entity\Menu $menu
     *
     * @return Acceso
     */
    public function setMenu(\BackendBundle\Entity\Menu $menu = null)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \BackendBundle\Entity\Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
