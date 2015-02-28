<?php

namespace ViKon\Diff\Entry;
use ViKon\Diff\Text;

/**
 * Class InsertedEntry
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Diff\Entry
 */
class InsertedEntry extends AbstractEntry {
    /**
     * @param \ViKon\Diff\Text $content
     * @param int              $newPosition
     */
    public function __construct(Text $content, $newPosition) {
        $this->content = $content[$newPosition];
        $this->newPosition = $newPosition;
    }
}