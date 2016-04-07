<?php
namespace Pronamic\Twinfield\Request\Read;

/**
 * Used to request a specific custom from a certain
 * office and code.
 * 
 * @package Pronamic\Twinfield
 * @subpackage Request\Read
 * @author Stephan Groen <stephan@picqer.com>
 * @version 0.0.1
 */
class Vat extends Read
{
    /**
     * Sets office and code if they are present.
     * 
     * @access public
     */
    public function __construct($office = null, $code = null)
    {
        parent::__construct();

        $this->add('type', 'vat');
        
        if (null !== $office) {
            $this->setOffice($office);
        }

        if (null !== $code) {
            $this->setCode($code);
        }
    }

    /**
     * Sets the office code for this Vat request.
     * 
     * @access public
     * @param int $office
     * @return \Pronamic\Twinfield\Request\Read\Vat
     */
    public function setOffice($office)
    {
        $this->add('office', $office);
        return $this;
    }

    /**
     * Sets the code for this Vat request.
     * 
     * @access public
     * @param string $code
     * @return \Pronamic\Twinfield\Request\Read\Vat
     */
    public function setCode($code)
    {
        $this->add('code', $code);
        return $this;
    }
}
