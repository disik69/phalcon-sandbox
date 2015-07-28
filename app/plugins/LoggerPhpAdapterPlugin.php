<?php

class LoggerPhpAdapterPlugin extends Phalcon\Logger\Adapter\File
{
    public function varDump($expression, $_ = null)
    {
        foreach (func_get_args() as $key => $value) {
            $output['OUTPUT ' . ++$key] = $value;
        }

        $this->debug('<?php' . PHP_EOL . var_export($output, true) . PHP_EOL . '?>');
    }
}
