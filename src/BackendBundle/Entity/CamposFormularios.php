<?php

namespace BackendBundle\Entity;

/**
 * CamposFormularios
 */
class CamposFormularios
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $campo;

    /**
     * @var string
     */
    private $valor;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var \BackendBundle\Entity\Formularios
     */
    private $formularios;


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
     * Set campo
     *
     * @param string $campo
     *
     * @return CamposFormularios
     */
    public function setCampo($campo)
    {
        $this->campo = $campo;

        return $this;
    }

    /**
     * Get campo
     *
     * @return string
     */
    public function getCampo()
    {
        return $this->campo;
    }

    /**
     * Set valor
     *
     * @param string $valor
     *
     * @return CamposFormularios
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return CamposFormularios
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
     * Set formularios
     *
     * @param \BackendBundle\Entity\Formularios $formularios
     *
     * @return CamposFormularios
     */
    public function setFormularios(\BackendBundle\Entity\Formularios $formularios = null)
    {
        $this->formularios = $formularios;

        return $this;
    }

    /**
     * Get formularios
     *
     * @return \BackendBundle\Entity\Formularios
     */
    public function getFormularios()
    {
        return $this->formularios;
    }
}

