<?php
/**
 * 
 * Mail-transport adapter using PHP's mail() function.
 * 
 * @category Genv
 * 
 * @package Genv_Mail
 * 
 * @author Paul M. Jones <pmjones@Genvphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id: Phpmail.php 3153 2008-05-05 23:14:16Z pmjones $
 * 
 */
class Genv_Mail_Transport_Adapter_Phpmail extends Genv_Mail_Transport_Adapter
{
    /**
     * 
     * Sends a Genv_Mail_Message using the mail() function.
     * 
     * @return bool True on success, false on failure.
     * 
     */
    protected function _send()
    {
        // all the message headers
        $headers = $this->_mail->fetchHeaders();
        
        // a list of to addressees
        $to_addr = array();
        
        // the subject line
        $subject = '';
        
        // retain and remove some header values
        foreach ($headers as $i => $header) {
            // retain and remove the "To:" addressees, because they get
            // added as a parameter to mail().
            if ($header[0] == 'To') {
                $to_addr[] = $header[1];
                unset($headers[$i]);
            }
            
            // also retain and remove the subject line.
            if ($header[0] == 'Subject') {
                $subject = $header[1];
                unset($headers[$i]);
            }
        }
        
        // try to send
        return mail(
            implode(', ', $to_addr),
            $subject,
            $this->_mail->fetchContent(),
            $this->_headersToString($headers)
        );
    }
}
