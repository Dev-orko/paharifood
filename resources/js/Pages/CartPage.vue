<template>
    <div>
        <Head title="শপিং কার্ট" />
        
        <Navbar />
        <Notification />
        
        <div class="cart-page">
            <div class="container">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">শপিং কার্ট</h1>
                    <div class="title-underline"></div>
                </div>

                <!-- Cart Content -->
                <div v-if="cartItems.length > 0" class="cart-content">
                    <!-- Cart Items Table -->
                    <div class="cart-items-section">
                        <div class="cart-table">
                            <div class="table-header">
                                <div class="header-cell product-col">পণ্য</div>
                                <div class="header-cell price-col">মূল্য</div>
                                <div class="header-cell quantity-col">পরিমাণ</div>
                                <div class="header-cell total-col">মোট</div>
                                <div class="header-cell action-col"></div>
                            </div>
                            
                            <div v-for="item in cartItems" :key="item.id" class="table-row">
                                <div class="table-cell product-col">
                                    <div class="product-info-cell">
                                        <Link :href="`/product/${item.product.slug}`" class="product-image">
                                            <img :src="item.product.image || '/images/placeholder.jpg'" :alt="item.product.name_bn" />
                                        </Link>
                                        <div class="product-details">
                                            <Link :href="`/product/${item.product.slug}`" class="product-name">
                                                {{ item.product.name_bn }}
                                            </Link>
                                            <p v-if="item.product.description_bn" class="product-desc">
                                                {{ item.product.description_bn }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table-cell price-col">
                                    <span class="price">৳{{ formatPrice(item.product.price) }}</span>
                                </div>
                                
                                <div class="table-cell quantity-col">
                                    <div class="quantity-selector">
                                        <button 
                                            @click="handleUpdateQuantity(item.id, item.quantity - 1)" 
                                            class="qty-btn"
                                            :disabled="item.quantity <= 1"
                                        >
                                            -
                                        </button>
                                        <input 
                                            type="number" 
                                            :value="item.quantity" 
                                            @change="handleUpdateQuantity(item.id, $event.target.value)"
                                            class="qty-input"
                                            min="1"
                                        />
                                        <button 
                                            @click="handleUpdateQuantity(item.id, item.quantity + 1)" 
                                            class="qty-btn"
                                        >
                                            +
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="table-cell total-col">
                                    <span class="total-price">৳{{ formatPrice(item.product.price * item.quantity) }}</span>
                                </div>
                                
                                <div class="table-cell action-col">
                                    <button @click="handleRemoveItem(item.id)" class="remove-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cart Summary -->
                    <div class="cart-summary-section">
                        <div class="summary-card">
                            <h2 class="summary-title">কার্ট সারসংক্ষেপ</h2>
                            
                            <div class="summary-row">
                                <span class="summary-label">সাবটোটাল:</span>
                                <span class="summary-value">৳{{ formatPrice(subtotal) }}</span>
                            </div>
                            
                            <div class="summary-row">
                                <span class="summary-label">ডেলিভারি চার্জ:</span>
                                <span class="summary-value">৳০.০০</span>
                            </div>
                            
                            <div class="summary-divider"></div>
                            
                            <div class="summary-row total-row">
                                <span class="summary-label">মোট:</span>
                                <span class="summary-value total-value">৳{{ formatPrice(total) }}</span>
                            </div>
                            
                            <Link href="/checkout" class="checkout-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                চেকআউট করুন
                            </Link>
                            
                            <button @click="handleClearCart" class="clear-cart-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                কার্ট খালি করুন
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty Cart State -->
                <div v-else class="empty-state">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h2 class="empty-title">আপনার কার্ট খালি</h2>
                    <p class="empty-message">কার্টে কোনো পণ্য নেই। এখনই কিনুন!</p>
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

const props = defineProps({
    cartItems: {
        type: Array,
        default: () => []
    },
    subtotal: {
        type: Number,
        default: 0
    },
    total: {
        type: Number,
        default: 0
    }
});

const { updateCartItem, removeFromCart, clearCart } = useCart();

const formatPrice = (price) => {
    return parseFloat(price).toFixed(2);
};

const handleUpdateQuantity = async (itemId, quantity) => {
    const newQuantity = parseInt(quantity);
    if (newQuantity > 0) {
        try {
            await updateCartItem(itemId, newQuantity);
        } catch (error) {
            console.error('Error updating quantity:', error);
        }
    }
};

const handleRemoveItem = async (itemId) => {
    try {
        await removeFromCart(itemId);
    } catch (error) {
        console.error('Error removing item:', error);
    }
};

const handleClearCart = async () => {
    try {
        await clearCart();
    } catch (error) {
        console.error('Error clearing cart:', error);
    }
};
</script>

<style scoped>
.cart-page {
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
    margin: 0 auto;
    border-radius: 2px;
}

/* Cart Content */
.cart-content {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 30px;
}

/* Cart Items Section */
.cart-items-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.cart-table {
    width: 100%;
}

.table-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1.2fr 1fr 80px;
    gap: 15px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e8e8e8;
    margin-bottom: 20px;
}

.header-cell {
    font-size: 14px;
    font-weight: 700;
    color: #1e272e;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1.2fr 1fr 80px;
    gap: 15px;
    padding: 20px 0;
    border-bottom: 1px solid #f1f2f6;
    align-items: center;
}

.table-row:last-child {
    border-bottom: none;
}

.table-cell {
    display: flex;
    align-items: center;
}

/* Product Info Cell */
.product-info-cell {
    display: flex;
    gap: 15px;
    align-items: center;
}

.product-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    background: #f8f9fa;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-details {
    flex: 1;
}

.product-name {
    font-size: 16px;
    font-weight: 700;
    color: #1e272e;
    text-decoration: none;
    display: block;
    margin-bottom: 5px;
    transition: color 0.2s;
}

.product-name:hover {
    color: #27ae60;
}

.product-desc {
    font-size: 13px;
    color: #95a5a6;
    margin: 0;
    line-height: 1.4;
}

/* Price */
.price {
    font-size: 18px;
    font-weight: 700;
    color: #27ae60;
}

/* Quantity Selector */
.quantity-selector {
    display: flex;
    align-items: center;
    border: 2px solid #e8e8e8;
    border-radius: 8px;
    overflow: hidden;
    width: fit-content;
}

.qty-btn {
    width: 35px;
    height: 40px;
    border: none;
    background: #f8f9fa;
    color: #1e272e;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
}

.qty-btn:hover:not(:disabled) {
    background: #27ae60;
    color: white;
}

.qty-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.qty-input {
    width: 60px;
    height: 40px;
    border: none;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    color: #1e272e;
}

.qty-input::-webkit-inner-spin-button,
.qty-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Total Price */
.total-price {
    font-size: 20px;
    font-weight: 800;
    color: #1e272e;
}

/* Remove Button */
.remove-btn {
    width: 40px;
    height: 40px;
    border: none;
    background: #fee;
    color: #e74c3c;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.remove-btn:hover {
    background: #e74c3c;
    color: white;
    transform: scale(1.1);
}

.remove-btn svg {
    width: 20px;
    height: 20px;
}

/* Cart Summary */
.cart-summary-section {
    position: sticky;
    top: 20px;
    height: fit-content;
}

.summary-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.summary-title {
    font-size: 22px;
    font-weight: 800;
    color: #1e272e;
    margin: 0 0 25px 0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.summary-label {
    font-size: 15px;
    color: #636e72;
}

.summary-value {
    font-size: 16px;
    font-weight: 700;
    color: #1e272e;
}

.summary-divider {
    height: 1px;
    background: #e8e8e8;
    margin: 20px 0;
}

.total-row {
    margin-top: 20px;
    margin-bottom: 25px;
}

.total-row .summary-label {
    font-size: 18px;
    font-weight: 700;
    color: #1e272e;
}

.total-value {
    font-size: 24px;
    font-weight: 800;
    color: #27ae60;
}

/* Checkout Button */
.checkout-btn {
    width: 100%;
    background: #27ae60;
    color: white;
    border: none;
    padding: 16px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-decoration: none;
    transition: all 0.2s;
    margin-bottom: 12px;
}

.checkout-btn:hover {
    background: #229954;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
}

.checkout-btn svg {
    width: 22px;
    height: 22px;
}

/* Clear Cart Button */
.clear-cart-btn {
    width: 100%;
    background: transparent;
    color: #e74c3c;
    border: 2px solid #e74c3c;
    padding: 14px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s;
}

.clear-cart-btn:hover {
    background: #e74c3c;
    color: white;
}

.clear-cart-btn svg {
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
    .cart-content {
        grid-template-columns: 1fr 350px;
    }
}

@media (max-width: 992px) {
    .cart-content {
        grid-template-columns: 1fr;
    }

    .cart-summary-section {
        position: static;
    }

    .table-header,
    .table-row {
        grid-template-columns: 1.5fr 0.8fr 1fr 0.8fr 60px;
        gap: 10px;
    }
}

@media (max-width: 768px) {
    .cart-page {
        padding: 30px 0 50px;
    }

    .page-title {
        font-size: 32px;
    }

    .cart-items-section {
        padding: 20px;
    }

    .table-header {
        display: none;
    }

    .table-row {
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 15px;
        border: none;
    }

    .product-info-cell {
        width: 100%;
    }

    .product-image {
        width: 100px;
        height: 100px;
    }

    .table-cell {
        width: 100%;
        justify-content: space-between;
    }

    .table-cell::before {
        content: attr(data-label);
        font-weight: 700;
        color: #1e272e;
    }

    .quantity-col::before {
        content: 'পরিমাণ:';
    }

    .price-col::before {
        content: 'মূল্য:';
    }

    .total-col::before {
        content: 'মোট:';
    }

    .action-col {
        justify-content: center;
    }

    .remove-btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
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
}
</style>
