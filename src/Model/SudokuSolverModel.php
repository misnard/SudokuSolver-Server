<?php

namespace SudokuServer\Model;

/**
 * @todo : clean repetitive code into different check methods
 *
 * Class SudokuSolverModel
 * @package SudokuServer\Model
 */
class SudokuSolverModel
{
    /**
     * @todo : prevent empty grid and perfs injury if we have too many possibilites in each cells
     *
     * Main method called by controller to init sudoku solve
     *
     * @param $sudokuData
     */
    public function exec($sudokuData)
    {
        $multipleResult = 0;
        $lineOffset = 0;
        foreach ($sudokuData as $lineData) {
            $excludedLinePossiblities = $this->getExcludedLinePossibilities($lineData);

            $cellOffset = 0;
            foreach ($lineData as $cell) {
                if (!$cell['value']) {
                    $excludedCollumnPossiblities = $this->getExcludedCollumnPossibilities($sudokuData, $cellOffset);
                    $excludedSquarePossiblities = $this->getExcludedSquarePossiblities($sudokuData, $cellOffset, $lineOffset);

                    $cellPossibilities = array_intersect(
                        $excludedSquarePossiblities,
                        $excludedCollumnPossiblities,
                        $excludedLinePossiblities
                    );

                    if (count($cellPossibilities) < 2) {
                        $sudokuData[$lineOffset][$cellOffset]['value'] = reset($cellPossibilities)[0];
                    } else {
                        $multipleResult++;
                    }
                }

                $cellOffset++;
            }

            $lineOffset++;
        }

        if ($multipleResult !== 0) {
            return $this->exec($sudokuData);
        }

        return $sudokuData;
    }

    /**
     * get excluded possibilites into a line
     *
     * @param $lineValue
     * @return array
     */
    protected function getExcludedLinePossibilities($lineValue)
    {
        $basePossibilities = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
        $excludedPossibilities = array();

        foreach ($lineValue as $value) {
            if (in_array($value['value'], $basePossibilities)) {
                $excludedPossibilities[] = $value['value'];
            }
        }

        return array_diff($basePossibilities, $excludedPossibilities);
    }

    /**
     * get excluded possibilites into a collumn
     *
     * @param $gridValue
     * @param $currentCellOffset
     * @return array
     */
    public function getExcludedCollumnPossibilities($gridValue, $currentCellOffset)
    {
        $basePossibilities = array('1', '2', '3', '4', '5', '6', '7', '8', '9');

        $excludedPossibilities = array();

        foreach ($gridValue as $lineValue) {
            if (in_array($lineValue[$currentCellOffset]['value'], $basePossibilities)) {
                $excludedPossibilities[] = $lineValue[$currentCellOffset]['value'];
            }
        }

        return array_diff($basePossibilities, $excludedPossibilities);
    }


    /**
     * get excluded possibilites into a square
     *
     * @param $gridValue
     * @param $currentCellOffset
     * @param $currentLineOffset
     * @return array
     */
    public function getExcludedSquarePossiblities($gridValue, $currentCellOffset, $currentLineOffset)
    {

        $basePossibilities = array('1', '2', '3', '4', '5', '6', '7', '8', '9');

        $excludedPossibilities = array();

        $squarePosition = $this->getSquarePositionBycell($currentCellOffset, $currentLineOffset);

        $x = 0;
        $y = 0;
        foreach ($basePossibilities as $possibility) {
            $currentCellValue = $gridValue[$x + ($squarePosition['height'] * 3)][$y + ($squarePosition['width'] * 3)];

            if (in_array($currentCellValue['value'], $basePossibilities)) {
                $excludedPossibilities[] = $currentCellValue['value'];
            }

            if ($y < 2) {
                $y++;
            } else {
                $y = 0;
                $x++;
            }
        }

        return array_diff($basePossibilities, $excludedPossibilities);
    }

    /**
     * get the current cell square postion into the grid
     *
     * @param $currentCellOffset
     * @param $currentLineOffset
     * @return array
     */
    public function getSquarePositionBycell($currentCellOffset, $currentLineOffset)
    {
        if ($currentCellOffset < 3) {
            if ($currentLineOffset < 3) {
                $squarePosition = array(
                    'height' => '0',
                    'width' => '0'
                );
            } elseif ($currentLineOffset < 6) {
                $squarePosition = array(
                    'height' => '1',
                    'width' => '0'
                );
            } elseif ($currentLineOffset < 9) {
                $squarePosition = array(
                    'height' => '2',
                    'width' => '0'
                );
            }
        } elseif ($currentCellOffset < 6) {
            if ($currentLineOffset < 3) {
                $squarePosition = array(
                    'height' => '0',
                    'width' => '1'
                );
            } elseif ($currentLineOffset < 6) {
                $squarePosition = array(
                    'height' => '1',
                    'width' => '1'
                );
            } elseif ($currentLineOffset < 9) {
                $squarePosition = array(
                    'height' => '2',
                    'width' => '1'
                );
            }
        } elseif ($currentCellOffset < 9) {
            if ($currentLineOffset < 3) {
                $squarePosition = array(
                    'height' => '0',
                    'width' => '2'
                );
            } elseif ($currentLineOffset < 6) {
                $squarePosition = array(
                    'height' => '1',
                    'width' => '2'
                );
            } elseif ($currentLineOffset < 9) {
                $squarePosition = array(
                    'height' => '2',
                    'width' => '2'
                );
            }
        }

        return $squarePosition;
    }
}