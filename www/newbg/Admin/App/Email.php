<?php

/*
邮件发送;

*/
 
class  EmailAction extends GlobalAction{
    
    
   public function sendemail(){
   
   
   
   
   
       /* $this->_layout = NULL;
        $form = Genv::factory("Genv_Form");
        $form->setElements(array('mail' => array('type'=>'text'),));

        // instantiat Acme_Filter class
        $filter = Genv::factory("Acme_Filter");

        // do it
        $form->setFilterLocaleObject($filter);
        $form->addFilter('mail', array('validateNotBlank'));
        $form->addFilter('mail', array('validateEmail'));
        $form->populate();

        if(!$form->validate())
        {
            $this->_jquery->addQuery("#sms_msg")->html($form->getInvalids("mail"))->fetch();

        }
        if ($this->_isProcess('send', 'btn_name'))
        {
            $addr = $this->_request->post('mail');
            // create a message object, and inject a transport config*/
            $mail = Genv::factory('Genv_Mail_Message', array(
                                       'transport' => array(
                                           'adapter' => 'Genv_Mail_Transport_Adapter_Smtp',
                                        )));
            // now tell the email to "send itself".
            // this uses the injected transport.
			$addr='Bryan@zheli.com';
            $mail->setFrom('cs@zheli.com')
                 ->addTo($addr, 'zheli')
                 ->setSubject('这是一封测试邮件');

            $mail->setHtml("<p>时尚生活从这里开始。<br>
			<a href='www.zheli.com'>点一点</a>
			</p>");

            // add an attachment
            //$file = Genv::$system.'/docroot/test_gd.png';
           // $mime = 'image/png';
           // $mail->attachFile($file, $mime);

            // ... compose the message ...
            $mail->send();
           // $this->_jquery->addQuery("#sms_msg")->html('发送成功！')->fetch();
       // }
   
   
   
   }
}
