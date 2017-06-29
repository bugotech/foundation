<?php namespace Bugotech\Foundation\Support;

use Exception;
use Bugotech\Foundation\Application;

class ExceptionAttrs extends Exception
{
    /**
     * Lista de mensagens por atributos.
     * @var array
     */
    protected $attrs = [];

    /**
     * @var Application
     */
    protected $app;

    public function __construct($message = '', $code = 0, array $attrs = [], \Exception $previous = null)
    {
        $this->app = app();
        $this->attrs = $attrs;

        $message = $this->app->runningInConsole() ? self::makeMsgs($message, $attrs) : $message;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Retorna as mensagens dos atributos.
     *
     * @return array
     */
    public function getAttrs()
    {
        return $this->attrs;
    }

    /**
     * Retornar a mensagem com as mensagens dos campos se houver.
     *
     * @return string
     */
    public function toMessageStr()
    {
        return $this->app->runningInConsole() ? $this->getMessage() : self::makeMsgs($this->getMessage(), $this->getAttrs());
    }

    /**
     * Monta a mensagem com os atributos.
     *
     * @param $message
     * @param $attrs
     * @return string
     */
    protected static function makeMsgs($message, $attrs)
    {
        $lines = [];
        foreach ($attrs as $attr => $msgs) {
            $lines[] = sprintf("%s: %s\r\n", $attr, implode('. ', $msgs));
        }

        return sprintf("%s\r\n%s", $message, implode("\r\n", $lines));
    }
}