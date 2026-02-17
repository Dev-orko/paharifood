import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { useNotification } from './useNotification';

export function useWishlist() {
    const { notify } = useNotification();

    const toggleWishlist = async (productId) => {
        try {
            const response = await axios.post('/wishlist/toggle', {
                product_id: productId
            });
            
            if (response.data.status === 'added') {
                notify({ message: 'পণ্যটি উইশলিস্টে যোগ করা হয়েছে!', type: 'wishlist' });
            } else {
                notify({ message: 'পণ্যটি উইশলিস্ট থেকে সরানো হয়েছে!', type: 'info' });
            }
            
            window.dispatchEvent(new Event('wishlist-updated'));
            
            return response.data;
        } catch (error) {
            if (error.response?.data?.message) {
                notify({ message: error.response.data.message, type: 'error' });
            } else {
                notify({ message: 'উইশলিস্ট আপডেট করতে সমস্যা হয়েছে!', type: 'error' });
            }
            throw error;
        }
    };

    const removeFromWishlist = async (itemId) => {
        try {
            await axios.delete(`/wishlist/${itemId}`);
            
            notify({ message: 'উইশলিস্ট থেকে সরানো হয়েছে!', type: 'warning' });
            window.dispatchEvent(new Event('wishlist-updated'));
            
            router.reload();
        } catch (error) {
            notify({ message: 'উইশলিস্ট থেকে মুছে ফেলতে সমস্যা হয়েছে!', type: 'error' });
            throw error;
        }
    };

    const getWishlistCount = async () => {
        try {
            const response = await axios.get('/wishlist/count');
            return response.data.count;
        } catch (error) {
            return 0;
        }
    };

    return {
        toggleWishlist,
        removeFromWishlist,
        getWishlistCount
    };
}
