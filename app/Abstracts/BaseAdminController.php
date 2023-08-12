<?php

namespace App\Abstracts;

use App\Interfaces\ProductDataHandlerInterface;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

abstract class BaseAdminController extends Controller
{
    protected $dataHandler;

    public function __construct(ProductDataHandlerInterface $dataHandler)
    {
        $this->dataHandler = $dataHandler;
    }

    abstract public function addProduct(Request $request);
}
