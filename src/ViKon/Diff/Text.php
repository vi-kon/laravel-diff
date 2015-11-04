<?php

namespace ViKon\Diff;

use ArrayAccess;
use Countable;

class Text implements ArrayAccess, Countable {

    /** @var string|string[] */
    private $source;

    /** @var array */
    private $options;

    /**
     * @param string $source
     * @param array  $options
     */
    public function __construct($source, array $options = []) {
        $this->options = $options;

        if(isset($this->options['compareWords']) && $this->options['compareWords']) {
            $this->source = preg_split('/[\s]+/', $source);
        } else if (isset($this->options['compareCharacters']) && $this->options['compareCharacters']) {
            $this->source = str_split($source);
        } else {
            $this->source = preg_split('/(\R)/', $source);
        }
    }

    public function offsetExists($offset) {
        return isset($this->source[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->source[$offset]) ? $this->source[$offset] : null;
    }

    public function offsetSet($offset, $value) {
        throw new DiffException('Set not allowed');
    }

    public function offsetUnset($offset) {
        throw new DiffException('Unset not allowed');
    }

    public function count() {
        return count($this->source);
    }}