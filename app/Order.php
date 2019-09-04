<?php

namespace App;

use App\Contracts\OrderContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model implements OrderContract
{
	public const PAYPAL = 'paypal';
	public const MERCADOPAGO = 'mp';
	public const STEAM = 'steam';

	public $incrementing = false;

	protected $with = ['orderable'];

	protected $appends = ['units', 'paid_units', 'paid', 'type', 'init_point'];

	protected $fillable = [
		'reason',
		'return_url',
		'cancel_url',
		'preset_amount',
		'payer_steam_id',
		'payer_tradelink',
		'unit_price',
		'unit_price_limit',
		'discount_per_unit',
		'avatar',
		'min_units',
		'max_units',
		'product_name_singular',
		'product_name_plural',
	];

	public function orderable()
	{
		return $this->morphTo();
	}

	public function getPaidUnitsAttribute()
	{
		$this->paidUnits($this);
	}

	public function getUnitsAttribute()
	{
		$this->units($this);
	}

	public function getPaidAttribute()
	{
		return $this->paid();
	}

	public function getTypeAttribute()
	{
		return $this->type();
	}

	public function getInitPointAttribute()
	{
		return route('orders.show', $this);
	}

	public function canInit($type)
	{
		/** @var OrderService $service */
		$service = app(OrderService::class);

		$class = $service->getClassByType($type);

		if ($class) {
			$c = app($class);

			return $c->canInit($this);
		} else {
			return false;
		}
	}

	public function recheck()
	{
		if ($this->orderable) {
			$this->increment('recheck_attempts');

			return $this->orderable->recheck();
		} else {
			return false;
		}
	}

	public function paid()
	{
		if ($this->orderable)
			return $this->orderable->paid();
		else
			return false;
	}

	public function status()
	{
		if ($this->orderable)
			return $this->orderable->status();
		else
			return false;
	}

	public function type()
	{
		if ($this->orderable)
			return $this->orderable->type();
		else
			return false;
	}

	public function units(Order $order)
	{
		if ($this->orderable) {
			return $this->orderable->units($order);
		} else {
			return false;
		}
	}

	public function paidUnits(Order $order)
	{
		if ($this->orderable) {
			return $this->orderable->paidUnits($order);
		} else {
			return false;
		}
	}
}
