<?php

namespace ViKon\Diff\Entry;
use ViKon\Diff\Text;

/**
 * Class DeletedEntry
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Diff\Entry
 */
class DeletedEntry extends AbstractEntry {
    /**
     * @param \ViKon\Diff\Text $content
     * @param int              $oldPosition
     */
    public function __construct(Text $content, $oldPosition) {
        $this->content = $content[$oldPosition];
        $this->oldPosition = $oldPosition;
    }
}