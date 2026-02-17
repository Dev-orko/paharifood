<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Show wishlist page with all wishlist items
     */
    public function index()
    {
        $wishlistItems = $this->getWishlistQuery()
            ->with('product.category')
            ->get();

        return inertia('WishlistPage', [
            'wishlistItems' => $wishlistItems,
        ]);
    }

    /**
     * Toggle product in wishlist (add if not exists, remove if exists)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $query = $this->getWishlistQuery()
            ->where('product_id', $request->product_id);

        $existingItem = $query->first();

        if ($existingItem) {
            $existingItem->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Product removed from wishlist',
                'count' => $this->getWishlistCount(),
            ]);
        }

        $data = [
            'product_id' => $request->product_id,
        ];

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        } else {
            $data['session_id'] = $this->getSessionId();
        }

        WishlistItem::create($data);

        return response()->json([
            'status' => 'added',
            'message' => 'Product added to wishlist',
            'count' => $this->getWishlistCount(),
        ]);
    }

    /**
     * Remove item from wishlist
     */
    public function remove($id)
    {
        $item = $this->getWishlistQuery()->findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Item removed from wishlist');
    }

    /**
     * Return wishlist items count
     */
    public function count()
    {
        return response()->json([
            'count' => $this->getWishlistCount(),
        ]);
    }

    /**
     * Get wishlist query for current user/guest
     */
    private function getWishlistQuery()
    {
        $query = WishlistItem::query();

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', $this->getSessionId());
        }

        return $query;
    }

    /**
     * Get wishlist items count
     */
    private function getWishlistCount()
    {
        return $this->getWishlistQuery()->count();
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
