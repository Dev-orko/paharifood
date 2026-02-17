<template>
    <div class="order-success-page">
        <Navbar />
        
        <div class="success-container">
            <div class="container">
                <div class="success-card">
                    <!-- Success Icon -->
                    <div class="success-icon-wrapper">
                        <div class="success-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <h1 class="success-title">আপনার অর্ডারটি সফলভাবে সম্পন্ন হয়েছে!</h1>
                    <p class="success-subtitle">ধন্যবাদ! আপনার অর্ডারটি আমরা গ্রহণ করেছি এবং শীঘ্রই প্রক্রিয়াকরণ শুরু হবে।</p>

                    <!-- Order Number -->
                    <div class="order-info">
                        <div class="order-number-label">অর্ডার নম্বর</div>
                        <div class="order-number-value">#{{ order.order_number }}</div>
                        <p class="order-note">এই নম্বরটি ব্যবহার করে আপনি আপনার অর্ডার ট্র্যাক করতে পারবেন</p>
                    </div>

                    <!-- Order Details -->
                    <div class="order-details">
                        <div class="detail-item">
                            <div class="detail-icon">📧</div>
                            <div class="detail-text">
                                <p class="detail-label">একটি নিশ্চিতকরণ ইমেইল পাঠানো হয়েছে</p>
                                <p class="detail-value">আপনার রেজিস্টার্ড ইমেইলে</p>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">🚚</div>
                            <div class="detail-text">
                                <p class="detail-label">ডেলিভারি সময়</p>
                                <p class="detail-value">৩-৫ কার্যদিবস</p>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">📞</div>
                            <div class="detail-text">
                                <p class="detail-label">যেকোনো সমস্যার জন্য</p>
                                <p class="detail-value">০১৭xxxxxxxx</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="/" class="btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            হোম পেজে ফিরে যান
                        </a>
                        <a href="/orders" class="btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            আমার অর্ডার দেখুন
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <Footer />
    </div>
</template>

<script setup>
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';

const props = defineProps({
    order: {
        type: Object,
        required: true
    }
});
</script>

<style scoped>
/* Container */
.order-success-page {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
}

.success-container {
    flex: 1;
    padding: 60px 0;
    display: flex;
    align-items: center;
}

.container {
    max-width: 700px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Success Card */
.success-card {
    background: white;
    padding: 50px 40px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Success Icon */
.success-icon-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 30px;
}

.success-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: scaleIn 0.5s ease-out;
}

.success-icon svg {
    width: 45px;
    height: 45px;
    color: white;
    stroke-width: 3;
}

@keyframes scaleIn {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Success Message */
.success-title {
    font-size: 1.8rem;
    color: #1a1a1a;
    margin-bottom: 12px;
    font-weight: 700;
    animation: fadeInUp 0.6s ease-out 0.2s both;
}

.success-subtitle {
    font-size: 1rem;
    color: #6b7280;
    margin-bottom: 35px;
    line-height: 1.6;
    animation: fadeInUp 0.6s ease-out 0.3s both;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Order Info */
.order-info {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
    animation: fadeInUp 0.6s ease-out 0.4s both;
}

.order-number-label {
    font-size: 0.9rem;
    color: #6b7280;
    margin-bottom: 8px;
}

.order-number-value {
    font-size: 2rem;
    color: #16a34a;
    font-weight: 700;
    margin-bottom: 10px;
    font-family: 'Courier New', monospace;
}

.order-note {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}

/* Order Details */
.order-details {
    display: grid;
    gap: 20px;
    margin-bottom: 35px;
    animation: fadeInUp 0.6s ease-out 0.5s both;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 15px;
    text-align: left;
    padding: 15px;
    background: #f9fafb;
    border-radius: 10px;
    transition: transform 0.2s ease;
}

.detail-item:hover {
    transform: translateX(5px);
}

.detail-icon {
    font-size: 2rem;
    flex-shrink: 0;
}

.detail-text {
    flex: 1;
}

.detail-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 4px;
}

.detail-value {
    font-size: 1rem;
    color: #1a1a1a;
    font-weight: 600;
    margin: 0;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
    animation: fadeInUp 0.6s ease-out 0.6s both;
}

.btn-primary,
.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(22, 163, 74, 0.4);
}

.btn-primary svg {
    width: 20px;
    height: 20px;
}

.btn-secondary {
    background: white;
    color: #16a34a;
    border: 2px solid #16a34a;
}

.btn-secondary:hover {
    background: #f0fdf4;
    transform: translateY(-3px);
}

.btn-secondary svg {
    width: 20px;
    height: 20px;
}

/* Responsive Design */
@media (max-width: 640px) {
    .success-container {
        padding: 30px 0;
    }
    
    .success-card {
        padding: 35px 25px;
    }
    
    .success-icon {
        width: 70px;
        height: 70px;
    }
    
    .success-icon svg {
        width: 38px;
        height: 38px;
    }
    
    .success-title {
        font-size: 1.5rem;
    }
    
    .success-subtitle {
        font-size: 0.95rem;
    }
    
    .order-number-value {
        font-size: 1.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 12px;
    }
    
    .btn-primary,
    .btn-secondary {
        width: 100%;
        justify-content: center;
    }
}
</style>
