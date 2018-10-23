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
    private $role;

    /**
     * @var \BackendBundle\Entity\Rrhh
     */
    private $rrhh;

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
     public function __toString() {
        return (string)$this->username;
    }
    public function serialize() {
        return serialize(array(
        $this->id,
        $this->username,
        $this->password,
        $this->empresa,
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
}
