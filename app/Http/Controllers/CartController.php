<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    /**
     * Display the cart page with all cart items
     */
    public function index()
    {
        $cartItems = $this->getCartItems();
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        $total = $subtotal; // Can add shipping, tax, etc. here
        
        return Inertia::render('CartPage', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $total,
        ]);
    }

    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        
        // Check if product is in stock
        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $identifier = $this->getCartIdentifier();
        
        // Check if item already exists in cart
        $cartItem = CartItem::where($identifier['key'], $identifier['value'])
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $validated['quantity'];
            
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Insufficient stock available.');
            }
            
            $cartItem->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            // Create new cart item
            CartItem::create([
                $identifier['key'] => $identifier['value'],
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price' => $product->price,
            ]);
        }

        return back()->with('success', 'Product added to cart successfully.');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $identifier = $this->getCartIdentifier();
        
        $cartItem = CartItem::where($identifier['key'], $identifier['value'])
            ->where('id', $id)
            ->with('product')
            ->firstOrFail();

        // Check if product is in stock
        if ($cartItem->product->stock < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock available.',
            ], 400);
        }

        $cartItem->update([
            'quantity' => $validated['quantity'],
        ]);

        $cartItems = $this->getCartItems();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully.',
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $identifier = $this->getCartIdentifier();
        
        $cartItem = CartItem::where($identifier['key'], $identifier['value'])
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
        ]);
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        $identifier = $this->getCartIdentifier();
        
        CartItem::where($identifier['key'], $identifier['value'])->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully.',
        ]);
    }

    /**
     * Return cart items count (API endpoint)
     */
    public function count()
    {
        $identifier = $this->getCartIdentifier();
        
        $count = CartItem::where($identifier['key'], $identifier['value'])->sum('quantity');

        return response()->json([
            'count' => $count,
        ]);
    }

    /**
     * Return cart total (API endpoint)
     */
    public function total()
    {
        $identifier = $this->getCartIdentifier();
        
        $cartItems = CartItem::where($identifier['key'], $identifier['value'])->get();
        
        $total = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return response()->json([
            'total' => $total,
        ]);
    }

    /**
     * Get cart items with product relationship and calculate totals
     */
    private function getCartItems()
    {
        $identifier = $this->getCartIdentifier();
        
        return CartItem::where($identifier['key'], $identifier['value'])
            ->with('product')
            ->get();
    }

    /**
     * Get cart identifier (user_id for authenticated users, session_id for guests)
     */
    private function getCartIdentifier()
    {
        if (Auth::check()) {
            return [
                'key' => 'user_id',
                'value' => Auth::id(),
            ];
        } else {
            return [
                'key' => 'session_id',
                'value' => session()->getId(),
            ];
        }
    }
}
