<?php

namespace BackendBundle\Entity;

/**
 * Presupuesto
 */
class Presupuesto
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $fechaIngreso;

    /**
     * @var float
     */
    private $valor;

    /**
     * @var \DateTime
     */
    private $fechaPago;

    /**
     * @var string
     */
    private $item;

    /**
     * @var string
     */
    private $comentario;

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
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return Presupuesto
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return Presupuesto
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     *
     * @return Presupuesto
     */
    public function setFechaPago($fechaPago)
    {
        $this->fechaPago = $fechaPago;

        return $this;
    }

    /**
     * Get fechaPago
     *
     * @return \DateTime
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * Set item
     *
     * @param string $item
     *
     * @return Presupuesto
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     *
     * @return Presupuesto
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set empresa
     *
     * @param \BackendBundle\Entity\Empresa $empresa
     *
     * @return Presupuesto
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
