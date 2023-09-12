<?php

namespace App\Http\Controllers\Order;

use App\Commands\Order\CreateOrderCommand;
use App\Http\Controllers\Controller;
use App\Http\DataFactory\Order\CreateOrderDataFactory;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Resources\Order\OrderResource;
use Illuminate\Support\Facades\DB;

class CreateOrderController extends Controller
{
    public function __construct()
    {
    }

    public function __invoke(CreateOrderRequest $request, CreateOrderCommand $createOrderCommand)
    {
        try {
            DB::beginTransaction();

            $createdOrder = $createOrderCommand->execute(CreateOrderDataFactory::fromCreateOrderRequest($request));

            DB::commit();
        } catch (\Exception $exception) {

            DB::rollBack();

            throw $exception;
        }
        // todo document the task

        return OrderResource::make($createdOrder);
    }
}
