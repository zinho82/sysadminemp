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
     * @var \BackendBundle\Entity\Config
     */
    private $tipoDocumento;

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
}

