<?php

namespace App\Http\Controllers\Order;

use App\Commands\Order\CreateOrderCommand;
use App\DTOs\Order\OrderData;
use App\Http\Controllers\Controller;
use App\Http\DataFactory\Order\CreateOrderDataFactory;
use App\Http\Requests\Order\CreateOrderRequest;
use Illuminate\Http\Request;

class CreateOrderController extends Controller
{
    public function __construct()
    {}

    public function __invoke(CreateOrderRequest $request,CreateOrderCommand $createOrderCommand)
    {

        $createOrderCommand->execute(CreateOrderDataFactory::fromCreateOrderRequest($request));
    }
}
