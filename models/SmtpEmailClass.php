<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 01.02.2017
 * Time: 14:20
 */
class SmtpEmail
{
    /** @var string $smtp_username - логин */
    public $smtp_username;

    /** @var string $smtp_password - пароль */
    public $smtp_password;

    /** @var string $smtp_host - хост */
    public $smtp_host;

    /** @var string $smtp_from - от кого */
    public $smtp_from;

    /** @var integer $smtp_port - порт */
    public $smtp_port;

    /** @var string $smtp_charset - кодировка */
    public $smtp_charset;

    public function __construct($smtp_username, $smtp_password, $smtp_host, $smtp_from, $smtp_port = 25, $smtp_charset = "utf-8") {
        $this->smtp_username = $smtp_username;
        $this->smtp_password = $smtp_password;
        $this->smtp_host = $smtp_host;
        $this->smtp_from = $smtp_from;
        $this->smtp_port = $smtp_port;
        $this->smtp_charset = $smtp_charset;
    }

    /**
     * @return SmtpEmail
     */
    public static function getNewInstance(){

        $login = Config::getValue(Config::SMTP_LOGIN);
        $pass = Config::getValue(Config::SMTP_PASS);
        $server = Config::getValue(Config::SMTP_SERVER);
        $from = Config::getValue(Config::SMTP_FROM);
        $port = Config::getValue(Config::SMTP_PORT);

        $instance = new self($login, $pass, $server, $from, $port);
        //$instance = new SmtpEmail(EMAIL_LOGIN, EMAIL_PASSWORD, EMAIL_SMTP, EMAIL_FROM, EMAIL_PORT);
        return $instance;
    }

    /**
     * Отправка письма
     *
     * @param string $mailTo - получатель письма
     * @param string $subject - тема письма
     * @param string $message - тело письма
     * @param string $headers - заголовки письма
     *
     * @return bool|string В случаи отправки вернет true, иначе текст ошибки    *
     */
    function send($mailTo, $subject, $message, $headers = null) {

        if (is_null($headers)) {
            $headers= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "From: HABB.KZ <info@habb.kz>\r\n";
        }

        $contentMail = "Date: " . date("D, d M Y H:i:s") . " UT\r\n";
        $contentMail .= 'Subject: =?' . $this->smtp_charset . '?B?'  . base64_encode($subject) . "=?=\r\n";
        $contentMail .= $headers . "\r\n";
        $contentMail .= $message . "\r\n";

        try {
            if(!$socket = @fsockopen($this->smtp_host, $this->smtp_port, $errorNumber, $errorDescription, 30)){
                throw new Exception($errorNumber.".".$errorDescription);
            }
            if (!$this->_parseServer($socket, "220")){
                throw new Exception('Connection error');
            }

            $server_name = $_SERVER["SERVER_NAME"];
            fputs($socket, "HELO $server_name\r\n");
            if (!$this->_parseServer($socket, "250")) {
                fclose($socket);
                throw new Exception('Error of command sending: HELO');
            }

            fputs($socket, "AUTH LOGIN\r\n");
            if (!$this->_parseServer($socket, "334")) {
                fclose($socket);
                throw new Exception('Autorization error');
            }



            fputs($socket, base64_encode($this->smtp_username) . "\r\n");
            if (!$this->_parseServer($socket, "334")) {
                fclose($socket);
                throw new Exception('Authorization error');
            }

            fputs($socket, base64_encode($this->smtp_password) . "\r\n");
            if (!$this->_parseServer($socket, "235")) {
                fclose($socket);
                throw new Exception('Authorization error');
            }

            fputs($socket, "MAIL FROM: <".$this->smtp_username.">\r\n");
            if (!$this->_parseServer($socket, "250")) {
                fclose($socket);
                throw new Exception('Error of command sending: MAIL FROM');
            }

            $mailTo = ltrim($mailTo, '<');
            $mailTo = rtrim($mailTo, '>');
            fputs($socket, "RCPT TO: <" . $mailTo . ">\r\n");
            if (!$this->_parseServer($socket, "250")) {
                fclose($socket);
                throw new Exception('Error of command sending: RCPT TO');
            }

            fputs($socket, "DATA\r\n");
            if (!$this->_parseServer($socket, "354")) {
                fclose($socket);
                throw new Exception('Error of command sending: DATA');
            }

            fputs($socket, $contentMail."\r\n.\r\n");
            if (!$this->_parseServer($socket, "250")) {
                fclose($socket);
                throw new Exception("E-mail didn't sent");
            }

            fputs($socket, "QUIT\r\n");
            fclose($socket);
        } catch (Exception $e) {
            return  $e->getMessage();
        }
        return true;
    }

    private function _parseServer($socket, $response) {
        while (@substr($responseServer, 3, 1) != ' ') {
            if (!($responseServer = fgets($socket, 256))) {
                return false;
            }
        }
        if (!(substr($responseServer, 0, 3) == $response)) {
            return false;
        }
        return true;

    }
}