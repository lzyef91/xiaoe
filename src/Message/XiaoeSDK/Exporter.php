<?php

namespace Nldou\Xiaoe\Message\XiaoeSDK;

class Exporter
{
    private $indentation = '    ';
    // TODO: private $this->addtypes = false; // type="string|int|float|array|null|bool"
    public function export($data)
    {
        echo '<?xml version="1.0" encoding="UTF-8">';
        $this->recurse($data, 0);
        echo PHP_EOL;
    }
    private function recurse($data, $level=0)
    {
        $indent = str_repeat($this->indentation, $level);
        foreach ($data as $key => $value) {
            echo PHP_EOL . $indent . '<' . $key;
            if ($value === null) {
                echo ' />';
            } else {
                echo '>';
                if (is_array($value)) {
                    if ($value) {
                        foreach ($value as $entry) {
                            $this->recurse(array($key => $entry), $level + 1);
                        }
                        echo PHP_EOL . $indent;
                    }
                } else if (is_object($value)) {
                    if ($value) {
                        $this->recurse($value, $level + 1);
                        echo PHP_EOL . $indent;
                    }
                } else {
                    if (is_bool($value)) {
                        $value = $value ? 'true' : 'false';
                    }
                    echo $value;
                }
                echo '</' . $key . '>';
            }
        }
    }
}