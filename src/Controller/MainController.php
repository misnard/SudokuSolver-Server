<?php

namespace SudokuServer\Controller;

use SudokuServer\Model\SudokuSolverModel as SudokuSolverModel;

/**
 * Class MainController
 * @package SudokuServer\Controller
 */
class MainController
{
    /**
     * @var SudokuSolverModel
     */
    private $sudokuSolverModel;

    /**
     * @todo : report this in Controller Abstract
     *
     * MainController constructor.
     * @param $request
     */
    public function __construct($request, $requestPostedData)
    {
        $this->sudokuSolverModel = new SudokuSolverModel;

        if (method_exists($this, $request)) {
            $this->$request($requestPostedData);
        } else {
            echo 'Error 404.';
        }
    }

    /**
     * @todo : return view to display api homepage
     */
    public function indexAction($requestPostedData)
    {
        echo "Hello to Sudoku Server API";
    }

    /**
     * Method called by Ajax from the client side
     */
    public function solveAction($requestPostedData)
    {
        $sudokuValues = json_decode(file_get_contents('php://input'), true);

        $result  = $this->sudokuSolverModel->exec($sudokuValues['data']);

        echo json_encode($result);

        return $result;
    }
}