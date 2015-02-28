<?php

namespace ViKon\Diff\Entry;

/**
 * Class AbstractEntry
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Diff\Entry
 */
abstract class AbstractEntry {

    /** @var string */
    protected $content;

    /** @var int|null */
    protected $oldPosition = null;

    /** @var int|null */
    protected $newPosition = null;

    /**
     * @return int|null
     */
    public function getOldPosition() {
        return $this->oldPosition;
    }

    /**
     * @return int|null
     */
    public function getNewPosition() {
        return $this->newPosition;
    }

    /**
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @return string
     */
    public function __toString() {
        return htmlspecialchars($this->content);
    }
}