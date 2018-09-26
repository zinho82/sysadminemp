<?php

namespace BackendBundle\Entity;

/**
 * Rrhh
 */
class Rrhh
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
    private $apellidoPaterno;

    /**
     * @var string
     */
    private $apellidoMaterno;

    /**
     * @var string
     */
    private $rut;

    /**
     * @var string
     */
    private $direccion;

    /**
     * @var string
     */
    private $comuna;

    /**
     * @var string
     */
    private $ciudad;

    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $sueldoBruto;

    /**
     * @var string
     */
    private $correoElectronico;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $cargo;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $afp;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $institucionSalud;

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Rrhh
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
     * Set apellidoPaterno
     *
     * @param string $apellidoPaterno
     *
     * @return Rrhh
     */
    public function setApellidoPaterno($apellidoPaterno)
    {
        $this->apellidoPaterno = $apellidoPaterno;

        return $this;
    }

    /**
     * Get apellidoPaterno
     *
     * @return string
     */
    public function getApellidoPaterno()
    {
        return $this->apellidoPaterno;
    }

    /**
     * Set apellidoMaterno
     *
     * @param string $apellidoMaterno
     *
     * @return Rrhh
     */
    public function setApellidoMaterno($apellidoMaterno)
    {
        $this->apellidoMaterno = $apellidoMaterno;

        return $this;
    }

    /**
     * Get apellidoMaterno
     *
     * @return string
     */
    public function getApellidoMaterno()
    {
        return $this->apellidoMaterno;
    }

    /**
     * Set rut
     *
     * @param string $rut
     *
     * @return Rrhh
     */
    public function setRut($rut)
    {
        $this->rut = $rut;

        return $this;
    }

    /**
     * Get rut
     *
     * @return string
     */
    public function getRut()
    {
        return $this->rut;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Rrhh
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set comuna
     *
     * @param string $comuna
     *
     * @return Rrhh
     */
    public function setComuna($comuna)
    {
        $this->comuna = $comuna;

        return $this;
    }

    /**
     * Get comuna
     *
     * @return string
     */
    public function getComuna()
    {
        return $this->comuna;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     *
     * @return Rrhh
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return Rrhh
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set sueldoBruto
     *
     * @param string $sueldoBruto
     *
     * @return Rrhh
     */
    public function setSueldoBruto($sueldoBruto)
    {
        $this->sueldoBruto = $sueldoBruto;

        return $this;
    }

    /**
     * Get sueldoBruto
     *
     * @return string
     */
    public function getSueldoBruto()
    {
        return $this->sueldoBruto;
    }

    /**
     * Set correoElectronico
     *
     * @param string $correoElectronico
     *
     * @return Rrhh
     */
    public function setCorreoElectronico($correoElectronico)
    {
        $this->correoElectronico = $correoElectronico;

        return $this;
    }

    /**
     * Get correoElectronico
     *
     * @return string
     */
    public function getCorreoElectronico()
    {
        return $this->correoElectronico;
    }

    /**
     * Set cargo
     *
     * @param \BackendBundle\Entity\Config $cargo
     *
     * @return Rrhh
     */
    public function setCargo(\BackendBundle\Entity\Config $cargo = null)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return \BackendBundle\Entity\Config
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set afp
     *
     * @param \BackendBundle\Entity\Config $afp
     *
     * @return Rrhh
     */
    public function setAfp(\BackendBundle\Entity\Config $afp = null)
    {
        $this->afp = $afp;

        return $this;
    }

    /**
     * Get afp
     *
     * @return \BackendBundle\Entity\Config
     */
    public function getAfp()
    {
        return $this->afp;
    }

    /**
     * Set institucionSalud
     *
     * @param \BackendBundle\Entity\Config $institucionSalud
     *
     * @return Rrhh
     */
    public function setInstitucionSalud(\BackendBundle\Entity\Config $institucionSalud = null)
    {
        $this->institucionSalud = $institucionSalud;

        return $this;
    }

    /**
     * Get institucionSalud
     *
     * @return \BackendBundle\Entity\Config
     */
    public function getInstitucionSalud()
    {
        return $this->institucionSalud;
    }

    /**
     * Set empresa
     *
     * @param \BackendBundle\Entity\Empresa $empresa
     *
     * @return Rrhh
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

