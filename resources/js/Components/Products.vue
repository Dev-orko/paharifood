<template>
    <section class="products-section">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header">
                <h2 class="section-title">বিশেষ পণ্যসমূহ</h2>
                <div class="title-underline"></div>
                <p class="section-subtitle">পাহাড়ের খাঁটি ও প্রাকৃতিক পণ্য সরাসরি আপনার দোরগোড়ায়</p>
            </div>

            <!-- Products Grid -->
            <div class="products-grid">
                <!-- Product Card -->
                <div v-for="product in products" :key="product.id" class="product-card">
                    <div class="card-top">
                        <div v-if="product.badge_bn" class="product-badge" :class="getBadgeClass(product.badge_bn)">{{ product.badge_bn }}</div>
                        <button @click="toggleWishlist(product.id)" class="wishlist-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                        <a :href="`/product/${product.slug}`" class="product-image">
                            <img :src="product.image" :alt="product.name_bn" />
                        </a>
                    </div>
                    <div class="product-info">
                        <a :href="`/product/${product.slug}`" class="product-name-link">
                            <h3 class="product-name">{{ product.name_bn }}</h3>
                        </a>
                        <p class="product-unit">{{ product.unit_bn }}</p>
                        <div class="product-rating">
                            <span class="stars">{{ getStars(product.rating) }}</span>
                            <span class="review-count">({{ product.reviews_count || 0 }})</span>
                        </div>
                        <div class="product-footer">
                            <div class="price-box">
                                <div class="price-info">
                                    <span class="current-price">৳{{ product.price }}</span>
                                    <span v-if="product.original_price && product.original_price > product.price" class="old-price">৳{{ product.original_price }}</span>
                                </div>
                                <span v-if="product.original_price && product.original_price > product.price" class="discount-badge">
                                    {{ getDiscountPercent(product.price, product.original_price) }}% ছাড়
                                </span>
                            </div>
                            <button @click="addToCart(product.id)" class="add-cart-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                যোগ করুন
                            </button>
                        </div>
                    </div>
                </div>

                <!-- End Products Grid -->
            </div>
        </div>
    </section>
</template>

<script setup>
import { useCart } from '../composables/useCart';
import { useWishlist } from '../composables/useWishlist';

const props = defineProps({
    products: {
        type: Array,
        default: () => [],
    },
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
    const fullStars = Math.floor(rating || 5);
    return '★'.repeat(fullStars) + '☆'.repeat(5 - fullStars);
};

const getDiscountPercent = (price, originalPrice) => {
    if (!originalPrice || originalPrice <= price) return 0;
    return Math.round(((originalPrice - price) / originalPrice) * 100);
};
</script>

<style scoped>
.products-section {
    padding: 70px 0;
    background: #f8f9fa;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Section Header */
.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-title {
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

.section-subtitle {
    font-size: 17px;
    color: #636e72;
    margin: 0;
    font-weight: 400;
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 24px;
}

/* Product Card */
.product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e8e8e8;
    display: flex;
    flex-direction: column;
    height: 100%;
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
}

.wishlist-btn svg {
    width: 18px;
    height: 18px;
    color: #e74c3c;
}

/* Product Image */
.product-image {
    width: 100%;
    height: 240px;
    overflow: hidden;
    position: relative;
    background: #f8f9fa;
    display: block;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Product Info */
.product-info {
    padding: 18px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.product-name-link {
    text-decoration: none;
    color: inherit;
}

.product-name {
    font-size: 16px;
    font-weight: 700;
    color: #1e272e;
    margin: 0 0 6px 0;
    line-height: 1.35;
    height: 44px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    transition: color 0.2s;
}

.product-unit {
    font-size: 13px;
    color: #7f8c8d;
    margin: 0 0 12px 0;
    font-weight: 500;
}

/* Product Rating */
.product-rating {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: auto;
    padding-bottom: 14px;
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
    margin-top: auto;
}

.price-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.price-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.current-price {
    font-size: 22px;
    font-weight: 800;
    color: #27ae60;
    line-height: 1;
}

.old-price {
    font-size: 14px;
    color: #95a5a6;
    text-decoration: line-through;
}

.discount-badge {
    background: #e74c3c;
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 4px;
    white-space: nowrap;
}

/* Add to Cart Button */
.add-cart-btn {
    width: 100%;
    background: #1e272e;
    color: white;
    border: none;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
}

.add-cart-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(39, 174, 96, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.add-cart-btn:hover::before {
    width: 300px;
    height: 300px;
}

.add-cart-btn:hover {
    background: #27ae60;
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 8px 20px rgba(39, 174, 96, 0.4);
}

.add-cart-btn:active {
    transform: translateY(0) scale(0.98);
}

.add-cart-btn svg {
    width: 18px;
    height: 18px;
}

/* Responsive Design */
@media (max-width: 1400px) {
    .products-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
    }
}

@media (max-width: 1200px) {
    .products-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    
    .product-image {
        height: 220px;
    }
}

@media (max-width: 992px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }
    
    .product-image {
        height: 200px;
    }
}

@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .section-title {
        font-size: 32px;
    }

    .product-image {
        height: 180px;
    }
    
    .product-info {
        padding: 14px;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .products-section {
        padding: 50px 0;
    }
    
    .product-image {
        height: 260px;
    }
}
</style>
