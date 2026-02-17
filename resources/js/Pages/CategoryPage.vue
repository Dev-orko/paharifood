<template>
    <div>
        <Head :title="category.name_bn" />
        
        <Navbar />
        <Notification />
        
        <div class="category-page">
            <div class="container">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">{{ category.name_bn }}</h1>
                    <div class="title-underline"></div>
                    <p v-if="category.description_bn" class="page-subtitle">{{ category.description_bn }}</p>
                </div>

                <!-- Products Grid -->
                <div v-if="products.length > 0" class="products-grid">
                    <div v-for="product in products" :key="product.id" class="product-card">
                        <div class="card-top">
                            <div v-if="product.badge_bn" class="product-badge" :class="getBadgeClass(product.badge_bn)">
                                {{ product.badge_bn }}
                            </div>
                            <button @click="handleToggleWishlist(product.id)" class="wishlist-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                            <Link :href="`/product/${product.slug}`" class="product-image-link">
                                <div class="product-image">
                                    <img :src="product.image || '/images/placeholder.jpg'" :alt="product.name_bn" />
                                </div>
                            </Link>
                        </div>
                        <div class="product-info">
                            <Link :href="`/product/${product.slug}`" class="product-name-link">
                                <h3 class="product-name">{{ product.name_bn }}</h3>
                            </Link>
                            <p v-if="product.description_bn" class="product-desc">{{ product.description_bn }}</p>
                            <div class="product-rating">
                                <span class="stars">{{ getStars(product.rating) }}</span>
                                <span class="review-count">({{ product.reviews_count || 0 }})</span>
                            </div>
                            <div class="product-footer">
                                <div class="price-box">
                                    <span class="current-price">৳{{ formatPrice(product.price) }}</span>
                                    <span v-if="product.original_price && product.original_price > product.price" class="old-price">
                                        ৳{{ formatPrice(product.original_price) }}
                                    </span>
                                </div>
                                <button @click="handleAddToCart(product.id)" class="add-cart-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    যোগ করুন
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="empty-state">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h2 class="empty-title">পণ্য পাওয়া যায়নি</h2>
                    <p class="empty-message">এই বিভাগে এখনো কোনো পণ্য যোগ করা হয়নি।</p>
                    <Link href="/" class="back-home-btn">হোম পেজে ফিরে যান</Link>
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
    category: {
        type: Object,
        required: true
    },
    products: {
        type: Array,
        default: () => []
    }
});

const { addToCart } = useCart();
const { toggleWishlist } = useWishlist();

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

const handleToggleWishlist = async (productId) => {
    try {
        await toggleWishlist(productId);
    } catch (error) {
        console.error('Error toggling wishlist:', error);
    }
};
</script>

<style scoped>
.category-page {
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
    max-width: 600px;
    margin: 0 auto;
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 26px;
}

/* Product Card */
.product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e8e8e8;
    transition: all 0.3s ease;
}

.product-card:hover {
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

/* Wishlist Button */
.wishlist-btn {
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

.wishlist-btn:hover {
    background: #ffebee;
    transform: scale(1.1);
}

.wishlist-btn svg {
    width: 18px;
    height: 18px;
    color: #e74c3c;
}

.wishlist-btn:hover svg {
    fill: #e74c3c;
}

/* Product Image */
.product-image-link {
    display: block;
}

.product-image {
    width: 100%;
    height: 200px;
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

.product-card:hover .product-image img {
    transform: scale(1.05);
}

/* Product Info */
.product-info {
    padding: 16px;
}

.product-name-link {
    text-decoration: none;
    color: inherit;
}

.product-name-link:hover .product-name {
    color: #27ae60;
}

.product-name {
    font-size: 15px;
    font-weight: 700;
    color: #1e272e;
    margin: 0 0 6px 0;
    line-height: 1.4;
    min-height: 42px;
    transition: color 0.2s;
}

.product-desc {
    font-size: 13px;
    color: #95a5a6;
    margin: 0 0 10px 0;
    line-height: 1.4;
}

/* Product Rating */
.product-rating {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 14px;
}

.stars {
    color: #f39c12;
    font-size: 12px;
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
    padding-top: 12px;
}

.price-box {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.current-price {
    font-size: 20px;
    font-weight: 800;
    color: #27ae60;
}

.old-price {
    font-size: 14px;
    color: #95a5a6;
    text-decoration: line-through;
}

/* Add to Cart Button */
.add-cart-btn {
    width: 100%;
    background: #1e272e;
    color: white;
    border: none;
    padding: 11px;
    border-radius: 8px;
    font-size: 13px;
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
    width: 16px;
    height: 16px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 30px;
    background: #f1f2f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon svg {
    width: 60px;
    height: 60px;
    color: #95a5a6;
}

.empty-title {
    font-size: 28px;
    font-weight: 700;
    color: #1e272e;
    margin: 0 0 12px 0;
}

.empty-message {
    font-size: 16px;
    color: #636e72;
    margin: 0 0 30px 0;
}

.back-home-btn {
    display: inline-block;
    background: #27ae60;
    color: white;
    padding: 14px 40px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.2s;
}

.back-home-btn:hover {
    background: #229954;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .products-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (max-width: 992px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .page-title {
        font-size: 36px;
    }
}

@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .page-title {
        font-size: 32px;
    }

    .product-image {
        height: 160px;
    }

    .category-page {
        padding: 30px 0 50px;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: 1fr;
    }

    .page-title {
        font-size: 28px;
    }

    .empty-state {
        padding: 60px 20px;
    }

    .empty-icon {
        width: 100px;
        height: 100px;
    }

    .empty-icon svg {
        width: 50px;
        height: 50px;
    }
}
</style>
