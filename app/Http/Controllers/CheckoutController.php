<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Show checkout page with cart items and totals
     */
    public function index()
    {
        $cartItems = $this->getCartQuery()
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $shippingCost = 60.00; // Fixed shipping cost, can be dynamic
        $total = $subtotal + $shippingCost;

        return inertia('CheckoutPage', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'total' => $total,
        ]);
    }

    /**
     * Process order placement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'city' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cod,bkash,nagad,rocket,card',
            'notes' => 'nullable|string',
        ]);

        // Get cart items
        $cartItems = $this->getCartQuery()
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty');
        }

        DB::beginTransaction();

        try {
            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
            $shippingCost = 60.00;
            $total = $subtotal + $shippingCost;

            // Create order
            $orderData = array_merge($validated, [
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount' => 0,
                'total' => $total,
                'payment_status' => $validated['payment_method'] === 'cod' ? 'pending' : 'pending',
                'order_status' => 'pending',
            ]);

            $order = Order::create($orderData);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'product_image' => $cartItem->product->image,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total' => $cartItem->price * $cartItem->quantity,
                ]);
            }

            // Clear cart
            $this->getCartQuery()->delete();

            DB::commit();

            return redirect()->route('orders.success', $order->id)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    /**
     * Show order success page
     */
    public function success(Order $order)
    {
        return inertia('OrderSuccess', [
            'order' => $order,
        ]);
    }

    /**
     * Get cart query for current user/guest
     */
    private function getCartQuery()
    {
        $query = CartItem::query();

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', $this->getSessionId());
        }

        return $query;
    }

    /**
     * Get or create session ID for guest users
     */
    private function getSessionId()
    {
        if (!session()->has('guest_session_id')) {
            session()->put('guest_session_id', uniqid('guest_', true));
        }
        return session()->get('guest_session_id');
    }
}
