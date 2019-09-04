<?php

namespace App\Http\Controllers;

use App\Classes\SteamAccount;
use App\Classes\SteamID;
use App\Exceptions\MPEmptyResponseException;
use App\Order;
use App\Services\SteamOrderService;
use App\SteamItem;
use App\SteamOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SteamOrderController extends Controller
{
	/**
	 * @param SteamOrderService $service
	 * @param Order             $order
	 *
	 * @return Order
	 * @throws \Exception
	 */
	public function init(SteamOrderService $service, Order $order)
	{
		if (empty($order->payer_tradelink))
			throw new \Exception('Unable to initialize order with Steam because it\'s missing the tradelink');

		$service->initialize($order);

		return redirect()->route('orders.show', $order);
	}

	public function execute(SteamOrderService $service, Request $request, Order $order)
	{
		$rawItems = collect($request->input('items'));

		// Decode items (they are passed as strings)
		$items = $rawItems->map(function ($item) {
			return json_decode($item, true);
		})->toArray();

		$service->execute($order, $items);

		return redirect()->route('orders.show', $order);
	}

	public function show(SteamOrderService $service, Order $order)
	{
		if ($order->status() === SteamOrder::ACCEPTED)
			return view('orders.order-success', compact('order'));

		if ($order->status() === SteamOrder::ACTIVE) {
			$tradeofferId = $order->orderable->tradeoffer_id;

			return view('orders.order-pending', compact('order', 'tradeofferId'));
		}

		if (!$order->orderable->tradeoffer_sent_at) {
			$items = $service->getInventory($order->payer_steam_id);

			return view('inventory', [
				'color' => 'blue',
				'width' => 'w-1/2',
				'items' => $items,
				'order' => $order,
			]);
		}

		return view('orders.order-error', compact('order'));
	}
}
