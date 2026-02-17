<template>
    <div>
        <Head :title="product.name_bn" />
        
        <Navbar />
        <Notification />
        
        <div class="product-detail-page">
            <div class="container">
                <div class="product-detail-wrapper">
                    <!-- Product Image Section -->
                    <div class="product-image-section">
                        <div class="main-image">
                            <img 
                                :src="product.image || '/images/placeholder.jpg'" 
                                :alt="product.name_bn"
                                class="product-img"
                            />
                        </div>
                    </div>

                    <!-- Product Info Section -->
                    <div class="product-info-section">
                        <!-- Breadcrumb -->
                        <div class="breadcrumb">
                            <Link href="/" class="breadcrumb-link">হোম</Link>
                            <span class="breadcrumb-separator">/</span>
                            <Link href="/products" class="breadcrumb-link">পণ্য</Link>
                            <span class="breadcrumb-separator">/</span>
                            <span class="breadcrumb-current">{{ product.category?.name_bn || 'বিভাগ' }}</span>
                        </div>

                        <!-- Product Name -->
                        <h1 class="product-name">{{ product.name_bn }}</h1>

                        <!-- Category -->
                        <div class="product-category">
                            <span class="category-label">বিভাগ:</span>
                            <span class="category-name">{{ product.category?.name_bn || 'N/A' }}</span>
                        </div>

                        <!-- Rating & Reviews -->
                        <div class="rating-section">
                            <div class="stars">
                                <span 
                                    v-for="i in 5" 
                                    :key="i"
                                    class="star"
                                    :class="{ 'filled': i <= Math.floor(product.rating || 4.5) }"
                                >
                                    ★
                                </span>
                            </div>
                            <span class="rating-text">{{ product.rating || 4.5 }}/5</span>
                            <span class="review-count">({{ product.reviews_count || 0 }} রিভিউ)</span>
                        </div>

                        <!-- Price Section -->
                        <div class="price-section">
                            <div class="current-price">৳{{ formatPrice(product.price) }}</div>
                            <div v-if="product.original_price && product.original_price > product.price" class="price-details">
                                <span class="original-price">৳{{ formatPrice(product.original_price) }}</span>
                                <span class="discount-badge">{{ calculateDiscount(product.original_price, product.price) }}% ছাড়</span>
                            </div>
                        </div>

                        <!-- Stock Status -->
                        <div class="stock-section">
                            <span 
                                class="stock-badge"
                                :class="stockStatusClass"
                            >
                                {{ stockStatusText }}
                            </span>
                            <span v-if="product.stock > 0" class="stock-count">
                                ({{ product.stock }} টি স্টকে আছে)
                            </span>
                        </div>

                        <!-- Description -->
                        <div class="description-section">
                            <h3 class="section-title">পণ্যের বিবরণ</h3>
                            <p class="description-text">{{ product.description_bn || 'বিবরণ উপলব্ধ নেই' }}</p>
                        </div>

                        <!-- Quantity & Actions -->
                        <div class="action-section">
                            <!-- Quantity Selector -->
                            <div class="quantity-selector">
                                <label class="quantity-label">পরিমাণ:</label>
                                <div class="quantity-input-wrapper">
                                    <button 
                                        @click="decreaseQuantity" 
                                        class="quantity-btn"
                                        :disabled="quantity <= 1"
                                    >
                                        -
                                    </button>
                                    <input 
                                        type="number" 
                                        v-model.number="quantity"
                                        :min="1"
                                        :max="product.stock"
                                        class="quantity-input"
                                        @input="validateQuantity"
                                    />
                                    <button 
                                        @click="increaseQuantity" 
                                        class="quantity-btn"
                                        :disabled="quantity >= product.stock"
                                    >
                                        +
                                    </button>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <button 
                                    @click="handleAddToCart"
                                    :disabled="product.stock <= 0 || isAddingToCart"
                                    class="btn btn-cart"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="btn-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    {{ isAddingToCart ? 'যোগ করা হচ্ছে...' : 'কার্টে যোগ করুন' }}
                                </button>
                                
                                <button 
                                    @click="handleToggleWishlist"
                                    :disabled="isTogglingWishlist"
                                    class="btn btn-wishlist"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" :fill="isInWishlist ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor" class="btn-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    {{ isInWishlist ? 'উইশলিস্টে আছে' : 'উইশলিস্টে যোগ করুন' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Footer />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Notification from '@/Components/Notification.vue';
import Footer from '@/Components/Footer.vue';
import { useCart } from '@/composables/useCart';
import { useWishlist } from '@/composables/useWishlist';

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});

const quantity = ref(1);
const isAddingToCart = ref(false);
const isTogglingWishlist = ref(false);
const isInWishlist = ref(false);

const { addToCart } = useCart();
const { toggleWishlist } = useWishlist();

// Format price with comma separator
const formatPrice = (price) => {
    return new Intl.NumberFormat('bn-BD').format(price);
};

// Calculate discount percentage
const calculateDiscount = (original, current) => {
    return Math.round(((original - current) / original) * 100);
};

// Stock status computed properties
const stockStatusClass = computed(() => {
    if (props.product.stock <= 0) return 'out-of-stock';
    if (props.product.stock < 10) return 'low-stock';
    return 'in-stock';
});

const stockStatusText = computed(() => {
    if (props.product.stock <= 0) return 'স্টকে নেই';
    if (props.product.stock < 10) return 'সীমিত স্টক';
    return 'স্টকে আছে';
});

// Quantity handlers
const increaseQuantity = () => {
    if (quantity.value < props.product.stock) {
        quantity.value++;
    }
};

const decreaseQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

const validateQuantity = () => {
    if (quantity.value < 1) quantity.value = 1;
    if (quantity.value > props.product.stock) quantity.value = props.product.stock;
};

// Add to cart handler
const handleAddToCart = async () => {
    if (props.product.stock <= 0) return;
    
    isAddingToCart.value = true;
    try {
        await addToCart(props.product.id, quantity.value);
    } catch (error) {
        console.error('Failed to add to cart:', error);
    } finally {
        isAddingToCart.value = false;
    }
};

// Toggle wishlist handler
const handleToggleWishlist = async () => {
    isTogglingWishlist.value = true;
    try {
        const result = await toggleWishlist(props.product.id);
        isInWishlist.value = result.status === 'added';
    } catch (error) {
        console.error('Failed to toggle wishlist:', error);
    } finally {
        isTogglingWishlist.value = false;
    }
};
</script>

<style scoped>
.product-detail-page {
    background: #f5f5f5;
    padding: 40px 0;
    min-height: calc(100vh - 200px);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.product-detail-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Product Image Section */
.product-image-section {
    position: sticky;
    top: 20px;
    height: fit-content;
}

.main-image {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e0e0e0;
    background: #fafafa;
}

.product-img {
    width: 100%;
    height: 600px;
    object-fit: cover;
    display: block;
}

/* Product Info Section */
.product-info-section {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Breadcrumb */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #666;
}

.breadcrumb-link {
    color: #228b22;
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb-link:hover {
    color: #1a6b1a;
    text-decoration: underline;
}

.breadcrumb-separator {
    color: #999;
}

.breadcrumb-current {
    color: #333;
}

/* Product Name */
.product-name {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.3;
    margin: 0;
}

/* Category */
.product-category {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
}

.category-label {
    color: #666;
}

.category-name {
    color: #228b22;
    font-weight: 600;
    padding: 4px 12px;
    background: #e8f5e8;
    border-radius: 20px;
}

/* Rating Section */
.rating-section {
    display: flex;
    align-items: center;
    gap: 12px;
}

.stars {
    display: flex;
    gap: 2px;
}

.star {
    font-size: 20px;
    color: #ddd;
}

.star.filled {
    color: #ffa500;
}

.rating-text {
    font-weight: 600;
    color: #333;
    font-size: 16px;
}

.review-count {
    color: #666;
    font-size: 14px;
}

/* Price Section */
.price-section {
    padding: 20px 0;
    border-top: 1px solid #e0e0e0;
    border-bottom: 1px solid #e0e0e0;
}

.current-price {
    font-size: 36px;
    font-weight: 700;
    color: #228b22;
}

.price-details {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 8px;
}

.original-price {
    font-size: 20px;
    color: #999;
    text-decoration: line-through;
}

.discount-badge {
    background: #ff4444;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
}

/* Stock Section */
.stock-section {
    display: flex;
    align-items: center;
    gap: 12px;
}

.stock-badge {
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
}

.stock-badge.in-stock {
    background: #e8f5e8;
    color: #228b22;
}

.stock-badge.low-stock {
    background: #fff3e0;
    color: #ff9800;
}

.stock-badge.out-of-stock {
    background: #ffebee;
    color: #f44336;
}

.stock-count {
    color: #666;
    font-size: 14px;
}

/* Description Section */
.description-section {
    padding: 20px;
    background: #fafafa;
    border-radius: 8px;
}

.section-title {
    font-size: 20px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 12px;
}

.description-text {
    color: #555;
    line-height: 1.8;
    font-size: 15px;
}

/* Action Section */
.action-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding-top: 20px;
}

/* Quantity Selector */
.quantity-selector {
    display: flex;
    align-items: center;
    gap: 16px;
}

.quantity-label {
    font-weight: 600;
    color: #333;
    font-size: 16px;
}

.quantity-input-wrapper {
    display: flex;
    align-items: center;
    border: 2px solid #228b22;
    border-radius: 8px;
    overflow: hidden;
}

.quantity-btn {
    width: 40px;
    height: 40px;
    border: none;
    background: #228b22;
    color: white;
    font-size: 20px;
    cursor: pointer;
    transition: background 0.2s;
}

.quantity-btn:hover:not(:disabled) {
    background: #1a6b1a;
}

.quantity-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.quantity-input {
    width: 60px;
    height: 40px;
    border: none;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.quantity-input::-webkit-inner-spin-button,
.quantity-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 16px;
}

.btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 16px 24px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-icon {
    width: 20px;
    height: 20px;
}

.btn-cart {
    background: #228b22;
    color: white;
}

.btn-cart:hover:not(:disabled) {
    background: #1a6b1a;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(34, 139, 34, 0.3);
}

.btn-cart:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
}

.btn-wishlist {
    background: white;
    color: #ff4444;
    border: 2px solid #ff4444;
}

.btn-wishlist:hover:not(:disabled) {
    background: #ff4444;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 68, 68, 0.3);
}

.btn-wishlist:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .product-detail-wrapper {
        grid-template-columns: 1fr;
        gap: 30px;
        padding: 20px;
    }

    .product-image-section {
        position: static;
    }

    .product-img {
        height: 400px;
    }

    .product-name {
        font-size: 24px;
    }

    .current-price {
        font-size: 28px;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .product-detail-page {
        padding: 20px 0;
    }

    .product-img {
        height: 300px;
    }

    .product-name {
        font-size: 20px;
    }

    .current-price {
        font-size: 24px;
    }

    .quantity-selector {
        flex-direction: column;
        align-items: flex-start;
    }

    .quantity-input-wrapper {
        width: 100%;
        justify-content: center;
    }
}
</style>
