<template>
    <div>
        <Head title="উইশলিস্ট" />
        
        <Navbar />
        <Notification />
        
        <div class="wishlist-page">
            <div class="container">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">আমার উইশলিস্ট</h1>
                    <div class="title-underline"></div>
                    <p v-if="wishlistItems.length > 0" class="page-subtitle">
                        আপনার পছন্দের {{ wishlistItems.length }} টি পণ্য
                    </p>
                </div>

                <!-- Wishlist Items Grid -->
                <div v-if="wishlistItems.length > 0" class="wishlist-grid">
                    <div v-for="item in wishlistItems" :key="item.id" class="wishlist-card">
                        <div class="card-top">
                            <div v-if="item.product.badge_bn" class="product-badge" :class="getBadgeClass(item.product.badge_bn)">
                                {{ item.product.badge_bn }}
                            </div>
                            <button @click="handleRemoveFromWishlist(item.id)" class="remove-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <Link :href="`/product/${item.product.slug}`" class="product-image-link">
                                <div class="product-image">
                                    <img :src="item.product.image || '/images/placeholder.jpg'" :alt="item.product.name_bn" />
                                </div>
                            </Link>
                        </div>
                        <div class="product-info">
                            <Link :href="`/product/${item.product.slug}`" class="product-name-link">
                                <h3 class="product-name">{{ item.product.name_bn }}</h3>
                            </Link>
                            <p v-if="item.product.description_bn" class="product-desc">
                                {{ item.product.description_bn }}
                            </p>
                            <div class="product-rating">
                                <span class="stars">{{ getStars(item.product.rating) }}</span>
                                <span class="review-count">({{ item.product.reviews_count || 0 }})</span>
                            </div>
                            <div class="product-footer">
                                <div class="price-box">
                                    <span class="current-price">৳{{ formatPrice(item.product.price) }}</span>
                                    <span v-if="item.product.original_price && item.product.original_price > item.product.price" class="old-price">
                                        ৳{{ formatPrice(item.product.original_price) }}
                                    </span>
                                </div>
                                <button @click="handleAddToCart(item.product.id)" class="add-cart-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    কার্টে যোগ করুন
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty Wishlist State -->
                <div v-else class="empty-state">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h2 class="empty-title">আপনার উইশলিস্ট খালি</h2>
                    <p class="empty-message">আপনি এখনো কোনো পণ্য পছন্দের তালিকায় যোগ করেননি।</p>
                    <Link href="/" class="shop-now-btn">কেনাকাটা শুরু করুন</Link>
                </div>
            </div>
        </div>
        
        <Footer />
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Notification from '@/Components/Notification.vue';
import Footer from '@/Components/Footer.vue';
import { useCart } from '@/composables/useCart';
import { useWishlist } from '@/composables/useWishlist';

const props = defineProps({
    wishlistItems: {
        type: Array,
        default: () => []
    }
});

const { addToCart } = useCart();
const { removeFromWishlist } = useWishlist();

const getBadgeClass = (badge) => {
    if (badge.includes('নতুন')) return 'new';
    if (badge.includes('ছাড়') || badge.includes('ডিসকাউন্ট')) return 'discount';
    if (badge.includes('জনপ্রিয়')) return 'hot';
    return '';
};

const getStars = (rating) => {
    const fullStars = Math.floor(rating || 4.5);
    return '★'.repeat(fullStars) + '☆'.repeat(5 - fullStars);
};

const formatPrice = (price) => {
    return parseFloat(price).toFixed(2);
};

const handleAddToCart = async (productId) => {
    try {
        await addToCart(productId, 1);
    } catch (error) {
        console.error('Error adding to cart:', error);
    }
};

const handleRemoveFromWishlist = async (itemId) => {
    try {
        await removeFromWishlist(itemId);
    } catch (error) {
        console.error('Error removing from wishlist:', error);
    }
};
</script>

<style scoped>
.wishlist-page {
    min-height: 100vh;
    background: #f8f9fa;
    padding: 50px 0 70px;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Page Header */
.page-header {
    text-align: center;
    margin-bottom: 50px;
}

.page-title {
    font-size: 42px;
    font-weight: 800;
    color: #1e272e;
    margin: 0 0 16px 0;
    letter-spacing: -1px;
}

.title-underline {
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #27ae60 0%, #229954 100%);
    margin: 0 auto 20px;
    border-radius: 2px;
}

.page-subtitle {
    font-size: 17px;
    color: #636e72;
    margin: 0;
}

/* Wishlist Grid */
.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 26px;
}

/* Wishlist Card */
.wishlist-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e8e8e8;
    transition: all 0.3s ease;
}

.wishlist-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

.card-top {
    position: relative;
}

/* Product Badge */
.product-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    z-index: 2;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.product-badge.new {
    background: #27ae60;
    color: white;
}

.product-badge.discount {
    background: #e74c3c;
    color: white;
}

.product-badge.hot {
    background: #f39c12;
    color: white;
}

/* Remove Button */
.remove-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 36px;
    height: 36px;
    background: white;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 2;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
    transition: all 0.2s;
}

.remove-btn:hover {
    background: #ffebee;
    transform: scale(1.1);
}

.remove-btn svg {
    width: 18px;
    height: 18px;
    color: #e74c3c;
}

/* Product Image */
.product-image-link {
    display: block;
}

.product-image {
    width: 100%;
    height: 240px;
    overflow: hidden;
    position: relative;
    background: #f8f9fa;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.wishlist-card:hover .product-image img {
    transform: scale(1.05);
}

/* Product Info */
.product-info {
    padding: 18px;
}

.product-name-link {
    text-decoration: none;
    color: inherit;
}

.product-name-link:hover .product-name {
    color: #27ae60;
}

.product-name {
    font-size: 16px;
    font-weight: 700;
    color: #1e272e;
    margin: 0 0 8px 0;
    line-height: 1.4;
    min-height: 44px;
    transition: color 0.2s;
}

.product-desc {
    font-size: 13px;
    color: #95a5a6;
    margin: 0 0 12px 0;
    line-height: 1.4;
}

/* Product Rating */
.product-rating {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 16px;
}

.stars {
    color: #f39c12;
    font-size: 13px;
    line-height: 1;
    letter-spacing: 2px;
}

.review-count {
    font-size: 12px;
    color: #95a5a6;
}

/* Product Footer */
.product-footer {
    border-top: 1px solid #f1f2f6;
    padding-top: 14px;
}

.price-box {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
}

.current-price {
    font-size: 22px;
    font-weight: 800;
    color: #27ae60;
}

.old-price {
    font-size: 15px;
    color: #95a5a6;
    text-decoration: line-through;
}

/* Add to Cart Button */
.add-cart-btn {
    width: 100%;
    background: #1e272e;
    color: white;
    border: none;
    padding: 13px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s;
}

.add-cart-btn:hover {
    background: #27ae60;
}

.add-cart-btn svg {
    width: 18px;
    height: 18px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 100px 20px;
}

.empty-icon {
    width: 140px;
    height: 140px;
    margin: 0 auto 30px;
    background: #f1f2f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon svg {
    width: 70px;
    height: 70px;
    color: #95a5a6;
}

.empty-title {
    font-size: 32px;
    font-weight: 800;
    color: #1e272e;
    margin: 0 0 15px 0;
}

.empty-message {
    font-size: 17px;
    color: #636e72;
    margin: 0 0 35px 0;
}

.shop-now-btn {
    display: inline-block;
    background: #27ae60;
    color: white;
    padding: 16px 50px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 17px;
    font-weight: 700;
    transition: all 0.2s;
}

.shop-now-btn:hover {
    background: #229954;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .wishlist-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 992px) {
    .wishlist-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .page-title {
        font-size: 36px;
    }
}

@media (max-width: 768px) {
    .wishlist-page {
        padding: 30px 0 50px;
    }

    .page-title {
        font-size: 32px;
    }

    .wishlist-grid {
        gap: 20px;
    }

    .product-image {
        height: 200px;
    }
}

@media (max-width: 480px) {
    .wishlist-grid {
        grid-template-columns: 1fr;
    }

    .page-title {
        font-size: 28px;
    }

    .empty-icon {
        width: 120px;
        height: 120px;
    }

    .empty-icon svg {
        width: 60px;
        height: 60px;
    }

    .empty-title {
        font-size: 26px;
    }

    .empty-state {
        padding: 80px 20px;
    }
}
</style>
