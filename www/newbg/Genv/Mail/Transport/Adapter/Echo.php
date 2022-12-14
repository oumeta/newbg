<?php
/**
 * 
 * Pseudo-transport that just prints the message headers and content.
 * 
 * @category Genv
 * 
 * @package Genv_Mail
 * 
 * @author Paul M. Jones <pmjones@Genvphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id: Echo.php 3153 2008-05-05 23:14:16Z pmjones $
 * 
 */
class Genv_Mail_Transport_Adapter_Echo extends Genv_Mail_Transport_Adapter
{
    /**
     * 
     * Prints the Genv_Mail_Message headers and content.
     * 
     * @return bool True on success, false on failure.
     * 
     */
    protected function _send()
    {
        echo $this->_headersToString($this->_mail->fetchHeaders());
        echo $this->_mail->getCrlf();
        echo $this->_mail->fetchContent();
        return true;
    }
}