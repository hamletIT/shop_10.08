<?php

namespace App\Abstracts;

use App\Interfaces\ProductDataHandlerInterface;
use App\Interfaces\CategoryDataHandlerInterface;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

abstract class BaseAdminController extends Controller
{
    protected $dataHandler;
    protected $categoryHandler;


    public function __construct(
        ProductDataHandlerInterface $dataHandler,
        CategoryDataHandlerInterface $categoryHandler
        )
    {
        $this->dataHandler = $dataHandler;
        $this->categoryHandler = $categoryHandler;
    }

    abstract public function addProduct(Request $request);
}
