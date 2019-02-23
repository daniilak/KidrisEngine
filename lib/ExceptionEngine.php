<?php
class ExceptionEngine
{

    public function __construct()
    {
        set_error_handler([$this, 'ErrorHandler']);
    }

    public function Logger($msg)
    {
        $date = date('[H:i:s d.m.Y]');
        file_put_contents(__DIR__ . '/logs/errors.txt', file_get_contents(__DIR__ . '/logs/errors.txt') . "{$date} {$msg}" . PHP_EOL);
    }

    public function ErrorHandler($errno, $errstr, $errfile, $errline)
    {
        require_once(__DIR__ . '/TemplateEngine.php');
        $template = new TemplateEngine('error_page.tpl');

        $template->templateSetVar('error', "
            Ошибка в файле {$errfile} на строке {$errline} <br>
            {$errstr}
        ");

        $this->Logger("Ошибка в файле {$errfile} на строке {$errline}: {$errstr}");
        
        $template->templateCompile();
        $template->templateDisplay();

        die();
    }
}