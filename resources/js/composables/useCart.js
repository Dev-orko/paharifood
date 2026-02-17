import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { useNotification } from './useNotification';

export function useCart() {
    const { notify } = useNotification();

    const addToCart = async (productId, quantity = 1) => {
        try {
            const response = await axios.post('/cart/add', {
                product_id: productId,
                quantity: quantity
            });
            
            notify({ message: 'পণ্যটি কার্টে যোগ করা হয়েছে!', type: 'cart' });
            
            window.dispatchEvent(new Event('cart-updated'));
            
            return response.data;
        } catch (error) {
            if (error.response?.data?.message) {
                notify({ message: error.response.data.message, type: 'error' });
            } else {
                notify({ message: 'কার্টে যোগ করতে সমস্যা হয়েছে!', type: 'error' });
            }
            throw error;
        }
    };

    const updateCartItem = async (itemId, quantity) => {
        try {
            const response = await axios.patch(`/cart/${itemId}`, {
                quantity: quantity
            });
            
            notify({ message: 'কার্ট আপডেট হয়েছে!', type: 'success' });
            window.dispatchEvent(new Event('cart-updated'));
            
            return response.data;
        } catch (error) {
            notify({ message: 'কার্ট আপডেট করতে সমস্যা হয়েছে!', type: 'error' });
            throw error;
        }
    };

    const removeFromCart = async (itemId) => {
        try {
            await axios.delete(`/cart/${itemId}`);
            
            window.dispatchEvent(new Event('cart-updated'));
            
            router.reload({
                only: ['cartItems', 'subtotal', 'total'],
                onSuccess: () => {
                    notify({ message: 'পণ্যটি কার্ট থেকে সরানো হয়েছে!', type: 'success' });
                }
            });
        } catch (error) {
            notify({ message: 'কার্ট থেকে মুছে ফেলতে সমস্যা হয়েছে!', type: 'error' });
            throw error;
        }
    };

    const clearCart = async () => {
        try {
            await axios.delete('/cart');
            
            window.dispatchEvent(new Event('cart-updated'));
            
            router.reload({
                only: ['cartItems', 'subtotal', 'total'],
                onSuccess: () => {
                    notify({ message: 'কার্ট খালি করা হয়েছে!', type: 'info' });
                }
            });
        } catch (error) {
            notify({ message: 'কার্ট খালি করতে সমস্যা হয়েছে!', type: 'error' });
            throw error;
        }
    };

    const getCartCount = async () => {
        try {
            const response = await axios.get('/cart/count');
            return response.data.count;
        } catch (error) {
            return 0;
        }
    };

    const getCartTotal = async () => {
        try {
            const response = await axios.get('/cart/total');
            return response.data.total;
        } catch (error) {
            return 0;
        }
    };

    return {
        addToCart,
        updateCartItem,
        removeFromCart,
        clearCart,
        getCartCount,
        getCartTotal
    };
}
