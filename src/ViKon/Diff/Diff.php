<?php

namespace ViKon\Diff;

use ViKon\Diff\Entry\DeletedEntry;
use ViKon\Diff\Entry\InsertedEntry;
use ViKon\Diff\Entry\UnmodifiedEntry;

class Diff {

    /** @var Text */
    private $old;

    /** @var Text */
    private $new;

    /** @var int */
    private $inserted = 0;

    /** @var int */
    private $deleted = 0;

    /** @var \ViKon\Diff\Entry\AbstractEntry[] */
    private $diff = [];

    /** @var \ViKon\Diff\Entry\AbstractEntry[][] */
    private $groups = [];

    /**
     * Compare two strings
     *
     * @param string $old     old source text
     * @param string $new     new source text
     * @param array  $options comparison options
     *
     * @return \ViKon\Diff\Diff
     */
    public static function compare($old, $new, array $options = []) {
        return new self($old, $new, $options);
    }

    /**
     * Compare two files content
     *
     * @param string $old     old source containing file path
     * @param string $new     new source containing file path
     * @param array  $options comparison options
     *
     * @return \ViKon\Diff\Diff
     */
    public static function compareFiles($old, $new, array $options = []) {
        return new self(file_get_contents($old), file_get_contents($new), $options);
    }

    /**
     * @param string $old
     * @param string $new
     * @param array  $options
     */
    protected function __construct($old, $new, array $options = []) {
        $this->old = new Text($old, $options);
        $this->new = new Text($new, $options);

        $diffTable = $this->buildDiffTable($this->old, $this->new);
        list($this->diff, $this->inserted, $this->deleted) = $this->buildDiff($this->old, $this->new, $diffTable);
        $this->groups = $this->buildGroups($this->diff, isset($options['offset']) && $options['offset'] ? $options['offset'] : 2);
    }

    /**
     * Get inserted changes count
     *
     * @return int
     */
    public function getInsertedCount() {
        return $this->inserted;
    }

    /**
     * Get deleted changes count
     *
     * @return int
     */
    public function getDeletedCount() {
        return $this->deleted;
    }

    /**
     * @return \ViKon\Diff\DiffGroup[]
     */
    public function getGroups() {
        return $this->groups;
    }

    public function toHTML($separator = '<br/>') {
        $output = '';

        foreach ($this->diff as $entry) {
            $element = '';
            if ($entry instanceof UnmodifiedEntry) {
                $element = 'span';
            } elseif ($entry instanceof DeletedEntry) {
                $element = 'del';
            } elseif ($entry instanceof InsertedEntry) {
                $element = 'ins';
            }
            $output .= '<' . $element . '>' . $entry . '</' . $element . '>' . $separator;
        }

        return $output;
    }

    /**
     * @param \ViKon\Diff\Text $old
     * @param \ViKon\Diff\Text $new
     *
     * @return array
     */
    private function buildDiffTable(Text $old, Text $new) {
        $diffTable = [array_fill(0, count($new) + 1, 0)];
        for ($row = 1; $row <= count($old); $row++) {
            $diffTable[$row] = [0];
            for ($col = 1; $col <= count($new); $col++) {
                $diffTable[$row][$col] = $old[$row - 1] === $new[$col - 1]
                    ? $diffTable[$row - 1][$col - 1] + 1
                    : max($diffTable[$row - 1][$col], $diffTable[$row][$col - 1]);
            }
        }

        return $diffTable;
    }

    /**
     * @param \ViKon\Diff\Text $old
     * @param \ViKon\Diff\Text $new
     * @param                  $diffTable
     *
     * @return array
     */
    private function buildDiff(Text $old, Text $new, $diffTable) {
        $inserted = 0;
        $deleted = 0;
        $diff = [];

        $row = count($this->old);
        $col = count($this->new);
        while ($row > 0 || $col > 0) {
            if ($row > 0 && $col > 0 && $this->old[$row - 1] === $this->new[$col - 1]) {
                $diff[] = new UnmodifiedEntry($old, $row - 1, $col - 1);
                $row--;
                $col--;
            } elseif ($col > 0 && $diffTable[$row][$col] === $diffTable[$row][$col - 1]) {
                $diff[] = new InsertedEntry($new, $col - 1);
                $inserted++;
                $col--;
            } else {
                $diff[] = new DeletedEntry($old, $row - 1);
                $deleted++;
                $row--;
            }
        }

        return [array_reverse($diff), $inserted, $deleted];
    }

    /**
     * @param     $diff
     * @param int $offset
     *
     * @return \ViKon\Diff\DiffGroup[]
     */
    private function buildGroups($diff, $offset = 2) {
        $groups = [];

        for ($i = 0; $i < count($diff); $i++) {
            if (!$diff[$i] instanceof UnmodifiedEntry) {
                $group = new DiffGroup();

                // Beginning offset
                for ($j = $i - 1; $j >= $i - $offset && $j >= 0; $j--) {
                    if ($diff[$j] instanceof UnmodifiedEntry) {
                        $group->addEntry($diff[$j], true);
                    }
                }

                for (; $i < count($diff) && !$diff[$i] instanceof UnmodifiedEntry; $i++) {
                    $group->addEntry($diff[$i]);
                }

                // Ending offset
                for ($j = $i; $j <= $i + $offset - 1 && $j < count($diff); $j++) {
                    if ($diff[$j] instanceof UnmodifiedEntry) {
                        $group->addEntry($diff[$j]);
                    }
                }

                $groups[] = $group;
            }
        }

        return $groups;
    }
}