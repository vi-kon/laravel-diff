<?php

namespace ViKon\Diff\Entry;

use ViKon\Diff\Text;

/**
 * Class UnmodifiedEntry
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Diff\Entry
 */
class UnmodifiedEntry extends AbstractEntry {

    /**
     * @param \ViKon\Diff\Text $content
     * @param int              $newPosition
     * @param int              $oldPosition
     */
    public function __construct(Text $content, $oldPosition, $newPosition) {
        $this->content = $content[$newPosition];
        $this->newPosition = $newPosition;
        $this->oldPosition = $oldPosition;
    }
}