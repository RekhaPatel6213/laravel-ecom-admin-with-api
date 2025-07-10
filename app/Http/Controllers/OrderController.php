<?php

namespace App\Http\Controllers;

use App\Jobs\SendOrderStatusJob;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderStatus;
use App\Traits\InventoryTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use InventoryTrait;

    protected $service;

    public function __construct()
    {
        $this->service = app('order.service');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return response()->json(['data' => $this->service->list($request)]);
            } else {
                return view('order.index');
            }
        } catch (Exception $exception) {
            return $this->abortJsonResponse($exception);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        try {
            $orderStatus = OrderStatus::where('status', 1)->pluck('order_status_name', 'id');
            $order = Order::where('id', $order->id)->with('orderproduct', 'orderhistory', 'orderstatus', 'billingaddress', 'shippingaddress')->first();
            return view('order.edit', compact('order', 'orderStatus'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    public function multiple_delete(Request $request)
    {
        $status = false;
        $message = 'Bad Request';

        if ($request->ajax()) {
            $data_id = json_decode($request->data_id);

            foreach ($data_id as $dkey => $dvalue) {
                $result = Order::where('id', $dvalue)->with('orderproduct')->first();

                if (!empty($result->productimage)) {
                    foreach ($result->productimage as $pikey => $pivalue) {
                        $pivalue->delete();
                    }
                }
                $result->delete();
            }

            $status = true;
            $message = 'Record successfully deleted';
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function order_history(Request $request)
    {
        if (OrderHistory::create($request->all())) {
            Order::where('id', $request->order_id)->update(['orderstatus_id' => $request->orderstatus_id]);

            // send Mail
            dispatch(new SendOrderStatusJob($request->order_id, $request->orderstatus_id, $request->comment));

            return redirect()->route('order.edit', [$request->order_id])->with('success_message', 'Data Successfully Submitted');
        } else {
            return redirect()->route('order.edit', [$request->order_id])->with('error_message', 'Opps something went wrong!');
        }
    }

    /**
     * Show My Order Details
     */
    public function my_order_details(string $order_no)
    {
        try {
            $order_data = Order::where(['order_no' => $order_no])->with('orderproduct', 'orderhistory', 'orderstatus', 'billingaddress', 'shippingaddress')->firstOrFail();

            return view('order.my_order_details', ['order_data' => $order_data]);
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }
    public function no_order_list(Request $request)
    {
        try {
            if ($request->ajax()) {
                return response()->json(['data' => $this->service->no_order_list()]);
            } else {
                return view('order.no_order');
            }
        } catch (Exception $exception) {
            return $this->abortJsonResponse($exception);
        }
    }
}
