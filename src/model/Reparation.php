<?php
namespace App\Model; 
class Reparation{
    public $id_workshop;

    public $id_reparation;

    public $name_workshop;

    public $register_date;

    public $license_plate;

    public function __construct(
        $id_workshop = null, 
        $id_reparation = null, 
        $name_workshop = null, 
        $register_date = null, 
        $license_plate = null
    ) {
        $this->id_workshop = $id_workshop;
        $this->id_reparation = $id_reparation;
        $this->name_workshop = $name_workshop;
        $this->register_date = $register_date;
        $this->license_plate = $license_plate;
    }

    /**
     * Get the value of id_workshop
     */ 
    public function getId_workshop()
    {
        return $this->id_workshop;
    }

    /**
     * Set the value of id_workshop
     *
     * @return  self
     */ 
    public function setId_workshop($id_workshop)
    {
        $this->id_workshop = $id_workshop;

        return $this;
    }

    /**
     * Get the value of id_reparation
     */ 
    public function getId_reparation()
    {
        return $this->id_reparation;
    }

    /**
     * Set the value of id_reparation
     *
     * @return  self
     */ 
    public function setId_reparation($id_reparation)
    {
        $this->id_reparation = $id_reparation;

        return $this;
    }

    /**
     * Get the value of name_workshop
     */ 
    public function getName_workshop()
    {
        return $this->name_workshop;
    }

    /**
     * Set the value of name_workshop
     *
     * @return  self
     */ 
    public function setName_workshop($name_workshop)
    {
        $this->name_workshop = $name_workshop;

        return $this;
    }

    /**
     * Get the value of register_date
     */ 
    public function getRegister_date()
    {
        return $this->register_date;
    }

    /**
     * Set the value of register_date
     *
     * @return  self
     */ 
    public function setRegister_date($register_date)
    {
        $this->register_date = $register_date;

        return $this;
    }

    /**
     * Get the value of license_plate
     */ 
    public function getLicense_plate()
    {
        return $this->license_plate;
    }

    /**
     * Set the value of license_plate
     *
     * @return  self
     */ 
    public function setLicense_plate($license_plate)
    {
        $this->license_plate = $license_plate;

        return $this;
    }
}




