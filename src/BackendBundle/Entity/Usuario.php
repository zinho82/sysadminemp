<?php

namespace BackendBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Usuario
 */
class Usuario implements UserInterface, \Serializable
{
    /**
     * @var integer
     */
    private $id;


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
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $app;

    /**
     * @var string
     */
    private $apm;

    /**
     * @var string
     */
    private $role;

    /**
     * @var string
     */
    private $correo;


    /**
     * Set username
     *
     * @param string $username
     *
     * @return Usuario
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Usuario
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
     * Set app
     *
     * @param string $app
     *
     * @return Usuario
     */
    public function setApp($app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * Get app
     *
     * @return string
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Set apm
     *
     * @param string $apm
     *
     * @return Usuario
     */
    public function setApm($apm)
    {
        $this->apm = $apm;

        return $this;
    }

    /**
     * Get apm
     *
     * @return string
     */
    public function getApm()
    {
        return $this->apm;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return Usuario
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set correo
     *
     * @param string $correo
     *
     * @return Usuario
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
     public function __toString() {
        return (string)$this->username;
    }
    public function serialize() {
        return serialize(array(
        $this->id,
        $this->username,
        $this->password
        ));
    }
    public function unserialize($serialized) {
        list(
        $this->id,
        $this->username,
        $this->password
                )= unserialize($serialized);
        
    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {
        return array("ROLE_ADMIN");
        
    }

    public function getSalt() {
        return null;
    }
    /**
     * @var string
     */
    private $image;


    /**
     * Set image
     *
     * @param string $image
     *
     * @return Usuario
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * @var \BackendBundle\Entity\Rrhh
     */
    private $rrhh;

    /**
     * @var \BackendBundle\Entity\Empresa
     */
    private $empresa;


    /**
     * Set rrhh
     *
     * @param \BackendBundle\Entity\Rrhh $rrhh
     *
     * @return Usuario
     */
    public function setRrhh(\BackendBundle\Entity\Rrhh $rrhh = null)
    {
        $this->rrhh = $rrhh;

        return $this;
    }

    /**
     * Get rrhh
     *
     * @return \BackendBundle\Entity\Rrhh
     */
    public function getRrhh()
    {
        return $this->rrhh;
    }

    /**
     * Set empresa
     *
     * @param \BackendBundle\Entity\Empresa $empresa
     *
     * @return Usuario
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
