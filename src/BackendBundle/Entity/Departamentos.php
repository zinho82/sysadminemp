<?php

namespace BackendBundle\Entity;

/**
 * Departamentos
 */
class Departamentos
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombreDepartamento;

    /**
     * @var string
     */
    private $descripcion;

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
     * Set nombreDepartamento
     *
     * @param string $nombreDepartamento
     *
     * @return Departamentos
     */
    public function setNombreDepartamento($nombreDepartamento)
    {
        $this->nombreDepartamento = $nombreDepartamento;

        return $this;
    }

    /**
     * Get nombreDepartamento
     *
     * @return string
     */
    public function getNombreDepartamento()
    {
        return $this->nombreDepartamento;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Departamentos
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
     * Set empresa
     *
     * @param \BackendBundle\Entity\Empresa $empresa
     *
     * @return Departamentos
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
