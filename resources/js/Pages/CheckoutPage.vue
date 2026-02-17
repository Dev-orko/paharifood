<template>
    <div class="checkout-page">
        <Navbar />
        
        <div class="checkout-container">
            <div class="container">
                <h1 class="page-title">চেকআউট</h1>
                
                <div class="checkout-grid">
                    <!-- Checkout Form -->
                    <div class="checkout-form-section">
                        <div class="form-card">
                            <h2 class="form-title">বিলিং তথ্য</h2>
                            
                            <form @submit.prevent="submitOrder">
                                <!-- Customer Name -->
                                <div class="form-group">
                                    <label for="customer_name" class="form-label">গ্রাহকের নাম <span class="required">*</span></label>
                                    <input 
                                        type="text" 
                                        id="customer_name"
                                        v-model="form.customer_name"
                                        class="form-input"
                                        :class="{ 'error': errors.customer_name }"
                                        placeholder="আপনার পূর্ণ নাম লিখুন"
                                    />
                                    <span v-if="errors.customer_name" class="error-message">{{ errors.customer_name }}</span>
                                </div>

                                <!-- Customer Email -->
                                <div class="form-group">
                                    <label for="customer_email" class="form-label">ইমেইল <span class="required">*</span></label>
                                    <input 
                                        type="email" 
                                        id="customer_email"
                                        v-model="form.customer_email"
                                        class="form-input"
                                        :class="{ 'error': errors.customer_email }"
                                        placeholder="example@email.com"
                                    />
                                    <span v-if="errors.customer_email" class="error-message">{{ errors.customer_email }}</span>
                                </div>

                                <!-- Customer Phone -->
                                <div class="form-group">
                                    <label for="customer_phone" class="form-label">ফোন নম্বর <span class="required">*</span></label>
                                    <input 
                                        type="tel" 
                                        id="customer_phone"
                                        v-model="form.customer_phone"
                                        class="form-input"
                                        :class="{ 'error': errors.customer_phone }"
                                        placeholder="০১৭xxxxxxxx"
                                    />
                                    <span v-if="errors.customer_phone" class="error-message">{{ errors.customer_phone }}</span>
                                </div>

                                <!-- Shipping Address -->
                                <div class="form-group">
                                    <label for="shipping_address" class="form-label">ঠিকানা <span class="required">*</span></label>
                                    <textarea 
                                        id="shipping_address"
                                        v-model="form.shipping_address"
                                        class="form-textarea"
                                        :class="{ 'error': errors.shipping_address }"
                                        rows="3"
                                        placeholder="আপনার সম্পূর্ণ ঠিকানা লিখুন"
                                    ></textarea>
                                    <span v-if="errors.shipping_address" class="error-message">{{ errors.shipping_address }}</span>
                                </div>

                                <!-- City and Postal Code Row -->
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="city" class="form-label">শহর <span class="required">*</span></label>
                                        <input 
                                            type="text" 
                                            id="city"
                                            v-model="form.city"
                                            class="form-input"
                                            :class="{ 'error': errors.city }"
                                            placeholder="শহরের নাম"
                                        />
                                        <span v-if="errors.city" class="error-message">{{ errors.city }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="postal_code" class="form-label">পোস্ট কোড</label>
                                        <input 
                                            type="text" 
                                            id="postal_code"
                                            v-model="form.postal_code"
                                            class="form-input"
                                            placeholder="১২০০"
                                        />
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="form-group">
                                    <label class="form-label">পেমেন্ট পদ্ধতি <span class="required">*</span></label>
                                    <div class="payment-methods">
                                        <label class="payment-option">
                                            <input 
                                                type="radio" 
                                                name="payment_method" 
                                                value="bKash"
                                                v-model="form.payment_method"
                                            />
                                            <span class="payment-label">
                                                <span class="payment-icon">📱</span>
                                                bKash
                                            </span>
                                        </label>

                                        <label class="payment-option">
                                            <input 
                                                type="radio" 
                                                name="payment_method" 
                                                value="Nagad"
                                                v-model="form.payment_method"
                                            />
                                            <span class="payment-label">
                                                <span class="payment-icon">📱</span>
                                                Nagad
                                            </span>
                                        </label>

                                        <label class="payment-option">
                                            <input 
                                                type="radio" 
                                                name="payment_method" 
                                                value="Rocket"
                                                v-model="form.payment_method"
                                            />
                                            <span class="payment-label">
                                                <span class="payment-icon">🚀</span>
                                                Rocket
                                            </span>
                                        </label>

                                        <label class="payment-option">
                                            <input 
                                                type="radio" 
                                                name="payment_method" 
                                                value="Card"
                                                v-model="form.payment_method"
                                            />
                                            <span class="payment-label">
                                                <span class="payment-icon">💳</span>
                                                Card
                                            </span>
                                        </label>

                                        <label class="payment-option">
                                            <input 
                                                type="radio" 
                                                name="payment_method" 
                                                value="COD"
                                                v-model="form.payment_method"
                                            />
                                            <span class="payment-label">
                                                <span class="payment-icon">💵</span>
                                                ক্যাশ অন ডেলিভারি
                                            </span>
                                        </label>
                                    </div>
                                    <span v-if="errors.payment_method" class="error-message">{{ errors.payment_method }}</span>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="submit-btn" :disabled="isSubmitting">
                                    <span v-if="!isSubmitting">অর্ডার নিশ্চিত করুন</span>
                                    <span v-else>প্রক্রিয়াকরণ হচ্ছে...</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary-section">
                        <div class="summary-card">
                            <h2 class="summary-title">অর্ডার সারাংশ</h2>
                            
                            <!-- Cart Items -->
                            <div class="cart-items">
                                <div v-for="item in cartItems" :key="item.id" class="cart-item">
                                    <div class="item-image">
                                        <img :src="item.image" :alt="item.name_bn" />
                                    </div>
                                    <div class="item-details">
                                        <h4 class="item-name">{{ item.name_bn }}</h4>
                                        <p class="item-quantity">পরিমাণ: {{ item.quantity }}</p>
                                    </div>
                                    <div class="item-price">
                                        ৳{{ (item.price * item.quantity).toFixed(2) }}
                                    </div>
                                </div>
                            </div>

                            <div class="summary-divider"></div>

                            <!-- Price Breakdown -->
                            <div class="price-breakdown">
                                <div class="price-row">
                                    <span class="price-label">সাবটোটাল:</span>
                                    <span class="price-value">৳{{ subtotal.toFixed(2) }}</span>
                                </div>
                                <div class="price-row">
                                    <span class="price-label">ডেলিভারি চার্জ:</span>
                                    <span class="price-value">৳{{ shippingCost.toFixed(2) }}</span>
                                </div>
                            </div>

                            <div class="summary-divider"></div>

                            <!-- Total -->
                            <div class="total-row">
                                <span class="total-label">মোট:</span>
                                <span class="total-value">৳{{ total.toFixed(2) }}</span>
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
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';

const props = defineProps({
    cartItems: {
        type: Array,
        required: true
    },
    subtotal: {
        type: Number,
        required: true
    },
    shippingCost: {
        type: Number,
        required: true,
        default: 60
    },
    total: {
        type: Number,
        required: true
    }
});

const form = reactive({
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    shipping_address: '',
    city: '',
    postal_code: '',
    payment_method: ''
});

const errors = ref({});
const isSubmitting = ref(false);

const validateForm = () => {
    errors.value = {};
    
    if (!form.customer_name.trim()) {
        errors.value.customer_name = 'নাম প্রয়োজন';
    }
    
    if (!form.customer_email.trim()) {
        errors.value.customer_email = 'ইমেইল প্রয়োজন';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.customer_email)) {
        errors.value.customer_email = 'বৈধ ইমেইল দিন';
    }
    
    if (!form.customer_phone.trim()) {
        errors.value.customer_phone = 'ফোন নম্বর প্রয়োজন';
    }
    
    if (!form.shipping_address.trim()) {
        errors.value.shipping_address = 'ঠিকানা প্রয়োজন';
    }
    
    if (!form.city.trim()) {
        errors.value.city = 'শহর প্রয়োজন';
    }
    
    if (!form.payment_method) {
        errors.value.payment_method = 'পেমেন্ট পদ্ধতি নির্বাচন করুন';
    }
    
    return Object.keys(errors.value).length === 0;
};

const submitOrder = () => {
    if (!validateForm()) {
        return;
    }
    
    isSubmitting.value = true;
    
    router.post('/checkout', form, {
        onSuccess: () => {
            isSubmitting.value = false;
        },
        onError: (serverErrors) => {
            errors.value = serverErrors;
            isSubmitting.value = false;
        }
    });
};
</script>

<style scoped>
/* Container */
.checkout-page {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: #f5f5f5;
}

.checkout-container {
    flex: 1;
    padding: 40px 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.page-title {
    font-size: 2rem;
    color: #1a1a1a;
    text-align: center;
    margin-bottom: 40px;
    font-weight: 700;
}

/* Grid Layout */
.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 30px;
}

/* Form Section */
.checkout-form-section {
    width: 100%;
}

.form-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.form-title {
    font-size: 1.5rem;
    color: #1a1a1a;
    margin-bottom: 25px;
    font-weight: 600;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 0.95rem;
    color: #333;
    margin-bottom: 8px;
    font-weight: 500;
}

.required {
    color: #dc2626;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}

.form-input.error,
.form-textarea.error {
    border-color: #dc2626;
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

.error-message {
    display: block;
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 6px;
}

/* Form Row */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* Payment Methods */
.payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 12px;
}

.payment-option {
    position: relative;
    cursor: pointer;
}

.payment-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.payment-label {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    background: white;
    transition: all 0.3s ease;
    font-weight: 500;
}

.payment-icon {
    font-size: 1.2rem;
}

.payment-option input[type="radio"]:checked + .payment-label {
    border-color: #16a34a;
    background: #f0fdf4;
    color: #16a34a;
}

.payment-option:hover .payment-label {
    border-color: #16a34a;
}

/* Submit Button */
.submit-btn {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    margin-top: 10px;
}

.submit-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(22, 163, 74, 0.3);
}

.submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Order Summary */
.order-summary-section {
    position: sticky;
    top: 20px;
    height: fit-content;
}

.summary-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.summary-title {
    font-size: 1.3rem;
    color: #1a1a1a;
    margin-bottom: 20px;
    font-weight: 600;
}

/* Cart Items */
.cart-items {
    margin-bottom: 20px;
}

.cart-item {
    display: flex;
    gap: 12px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f3f4f6;
}

.cart-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.item-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details {
    flex: 1;
}

.item-name {
    font-size: 0.95rem;
    color: #1a1a1a;
    margin-bottom: 4px;
    font-weight: 500;
}

.item-quantity {
    font-size: 0.85rem;
    color: #6b7280;
}

.item-price {
    font-weight: 600;
    color: #16a34a;
    align-self: center;
}

.summary-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 20px 0;
}

/* Price Breakdown */
.price-breakdown {
    margin-bottom: 20px;
}

.price-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 0.95rem;
}

.price-label {
    color: #6b7280;
}

.price-value {
    color: #1a1a1a;
    font-weight: 500;
}

/* Total */
.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
}

.total-label {
    font-size: 1.2rem;
    color: #1a1a1a;
    font-weight: 600;
}

.total-value {
    font-size: 1.5rem;
    color: #16a34a;
    font-weight: 700;
}

/* Responsive Design */
@media (max-width: 992px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }
    
    .order-summary-section {
        position: static;
        order: -1;
    }
}

@media (max-width: 640px) {
    .checkout-container {
        padding: 20px 0;
    }
    
    .page-title {
        font-size: 1.5rem;
        margin-bottom: 25px;
    }
    
    .form-card,
    .summary-card {
        padding: 20px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .payment-methods {
        grid-template-columns: 1fr;
    }
}
</style>
