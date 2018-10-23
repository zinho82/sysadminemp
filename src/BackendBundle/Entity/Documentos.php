<?php

namespace BackendBundle\Entity;

/**
 * Documentos
 */
class Documentos
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $archivo;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var \DateTime
     */
    private $fechaCarga;

    /**
     * @var \BackendBundle\Entity\Facturas
     */
    private $facturas;

    /**
     * @var \BackendBundle\Entity\Config
     */
    private $tipoDocumento;

    /**
     * @var \BackendBundle\Entity\Empresa
     */
    private $empresa;

    /**
     * @var \BackendBundle\Entity\Rrhh
     */
    private $rrhh;

    /**
     * @var \BackendBundle\Entity\Usuario
     */
    private $cargadoPor;


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
     * Set archivo
     *
     * @param string $archivo
     *
     * @return Documentos
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;

        return $this;
    }

    /**
     * Get archivo
     *
     * @return string
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Documentos
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
     * Set fechaCarga
     *
     * @param \DateTime $fechaCarga
     *
     * @return Documentos
     */
    public function setFechaCarga($fechaCarga)
    {
        $this->fechaCarga = $fechaCarga;

        return $this;
    }

    /**
     * Get fechaCarga
     *
     * @return \DateTime
     */
    public function getFechaCarga()
    {
        return $this->fechaCarga;
    }

    /**
     * Set facturas
     *
     * @param \BackendBundle\Entity\Facturas $facturas
     *
     * @return Documentos
     */
    public function setFacturas(\BackendBundle\Entity\Facturas $facturas = null)
    {
        $this->facturas = $facturas;

        return $this;
    }

    /**
     * Get facturas
     *
     * @return \BackendBundle\Entity\Facturas
     */
    public function getFacturas()
    {
        return $this->facturas;
    }

    /**
     * Set tipoDocumento
     *
     * @param \BackendBundle\Entity\Config $tipoDocumento
     *
     * @return Documentos
     */
    public function setTipoDocumento(\BackendBundle\Entity\Config $tipoDocumento = null)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return \BackendBundle\Entity\Config
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set empresa
     *
     * @param \BackendBundle\Entity\Empresa $empresa
     *
     * @return Documentos
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
     * Set rrhh
     *
     * @param \BackendBundle\Entity\Rrhh $rrhh
     *
     * @return Documentos
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
     * Set cargadoPor
     *
     * @param \BackendBundle\Entity\Usuario $cargadoPor
     *
     * @return Documentos
     */
    public function setCargadoPor(\BackendBundle\Entity\Usuario $cargadoPor = null)
    {
        $this->cargadoPor = $cargadoPor;

        return $this;
    }

    /**
     * Get cargadoPor
     *
     * @return \BackendBundle\Entity\Usuario
     */
    public function getCargadoPor()
    {
        return $this->cargadoPor;
    }
}

