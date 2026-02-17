<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class AccountController extends Controller
{
    /**
     * Show the account settings page.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $orders = $user->orders()
            ->with('items.product')
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('AccountSettings', [
            'user' => array_merge($user->toArray(), [
                'avatar_url' => $user->avatar_url,
            ]),
            'orders' => $orders,
        ]);
    }

    /**
     * Update avatar/profile photo.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = $request->user();

        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success', 'প্রোফাইল ছবি আপডেট হয়েছে');
    }

    /**
     * Remove avatar.
     */
    public function removeAvatar(Request $request)
    {
        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return back()->with('success', 'প্রোফাইল ছবি মুছে ফেলা হয়েছে');
    }

    /**
     * Update personal information.
     */
    public function updatePersonal(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->user()->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $user = $request->user();
        $user->fill($request->only('name', 'email', 'phone', 'date_of_birth', 'gender'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('success', 'ব্যক্তিগত তথ্য আপডেট হয়েছে');
    }

    /**
     * Update shipping address.
     */
    public function updateShipping(Request $request)
    {
        $request->validate([
            'shipping_address' => 'nullable|string|max:500',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:10',
            'shipping_division' => 'nullable|string|max:100',
        ]);

        $request->user()->update($request->only(
            'shipping_address', 'shipping_city', 'shipping_postal_code', 'shipping_division'
        ));

        return back()->with('success', 'ডেলিভারি ঠিকানা আপডেট হয়েছে');
    }

    /**
     * Update billing address.
     */
    public function updateBilling(Request $request)
    {
        $request->validate([
            'billing_address' => 'nullable|string|max:500',
            'billing_city' => 'nullable|string|max:100',
            'billing_postal_code' => 'nullable|string|max:10',
            'billing_division' => 'nullable|string|max:100',
        ]);

        $request->user()->update($request->only(
            'billing_address', 'billing_city', 'billing_postal_code', 'billing_division'
        ));

        return back()->with('success', 'বিলিং ঠিকানা আপডেট হয়েছে');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'পাসওয়ার্ড পরিবর্তন হয়েছে');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'order_updates' => 'boolean',
            'promotional_emails' => 'boolean',
            'preferred_payment' => 'nullable|string|in:cod,bkash,nagad,rocket,card',
        ]);

        $request->user()->update($request->only(
            'email_notifications', 'sms_notifications', 'order_updates',
            'promotional_emails', 'preferred_payment'
        ));

        return back()->with('success', 'নোটিফিকেশন সেটিংস আপডেট হয়েছে');
    }

    /**
     * Delete account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = $request->user();

        // Delete avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
