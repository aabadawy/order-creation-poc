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
        //todo implement authentication endpoint (login) and remove force setUser as auth
        auth()->setUser(\App\Models\User::query()->first());
    }

    public function __invoke(CreateOrderRequest $request, CreateOrderCommand $createOrderCommand)
    {
        try {
            DB::beginTransaction();

            $createdOrder = $createOrderCommand->execute((new CreateOrderDataFactory())->from($request));

            DB::commit();
        } catch (\Exception $exception) {

            DB::rollBack();

            throw $exception;
        }

        return OrderResource::make($createdOrder);
    }
}
