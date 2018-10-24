<?php

namespace BackendBundle\Entity;

/**
 * Registros
 */
class Registros
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $registro;

    /**
     * @var \DateTime
     */
    private $fechaRegistro;

    /**
     * @var \BackendBundle\Entity\Empresa
     */
    private $empresa;

    /**
     * @var \BackendBundle\Entity\Usuario
     */
    private $realizadoPor;


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
     * Set registro
     *
     * @param string $registro
     *
     * @return Registros
     */
    public function setRegistro($registro)
    {
        $this->registro = $registro;

        return $this;
    }

    /**
     * Get registro
     *
     * @return string
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     *
     * @return Registros
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set empresa
     *
     * @param \BackendBundle\Entity\Empresa $empresa
     *
     * @return Registros
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
     * Set realizadoPor
     *
     * @param \BackendBundle\Entity\Usuario $realizadoPor
     *
     * @return Registros
     */
    public function setRealizadoPor(\BackendBundle\Entity\Usuario $realizadoPor = null)
    {
        $this->realizadoPor = $realizadoPor;

        return $this;
    }

    /**
     * Get realizadoPor
     *
     * @return \BackendBundle\Entity\Usuario
     */
    public function getRealizadoPor()
    {
        return $this->realizadoPor;
    }
}
