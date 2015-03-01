<?php
/**
 * Created by PhpStorm.
 * User: van Gogh
 * Date: 2015.03.01.
 * Time: 0:10
 */

namespace ViKon\Diff;


use ArrayAccess;
use Countable;
use ViKon\Diff\Entry\AbstractEntry;

class DiffGroup implements ArrayAccess, Countable {

    /** @var \ViKon\Diff\Entry\AbstractEntry[] */
    private $entries = [];

    /**
     * Add new entry to group
     *
     * @param \ViKon\Diff\Entry\AbstractEntry $entry
     * @param bool                            $prepend prepend or not entry to group
     */
    public function addEntry(AbstractEntry $entry, $prepend = false) {
        if ($prepend) {
            array_unshift($this->entries, $entry);
        } else {
            $this->entries[] = $entry;
        }
    }

    /**
     * @return int
     */
    public function getFirstPosition() {
        return reset($this->entries)->getNewPosition();
    }

    /**
     * @return int
     */
    public function getLastPosition() {
        return end($this->entries)->getNewPosition();
    }

    public function getEntries() {
        return $this->entries;
    }

    public function offsetExists($offset) {
        return isset($this->entries[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->entries[$offset]) ? $this->entries[$offset] : null;
    }

    public function offsetSet($offset, $value) {
        throw new DiffException('Set is not supported');
    }

    public function offsetUnset($offset) {
        throw new DiffException('Unset is not supported');
    }

    /**
     * Get entries count
     *
     * @return int
     */
    public function count() {
        return count($this->entries);
    }}