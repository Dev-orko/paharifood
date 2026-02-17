<template>
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-wrapper">
                <!-- Icon and Title -->
                <div class="newsletter-header">
                    <div class="newsletter-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="newsletter-text">
                        <h2 class="newsletter-title">নিউজলেটারে যোগ দিন এবং ১০% ছাড় পান</h2>
                        <p class="newsletter-subtitle">বিশেষ অফার এবং নতুন পণ্যের আপডেট পান</p>
                    </div>
                </div>

                <!-- Subscription Form -->
                <form @submit.prevent="handleSubmit" class="subscribe-form">
                    <div class="form-fields">
                        <input 
                            type="email" 
                            v-model="formData.email"
                            placeholder="আপনার ইমেইল ঠিকানা লিখুন"
                            required
                            class="email-input"
                        />
                        <button type="submit" class="submit-btn" :disabled="isSubmitting">
                            <span v-if="!isSubmitting">সাবস্ক্রাইব করুন</span>
                            <span v-else>
                                <svg class="spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                    <p class="form-note">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        আমরা স্প্যাম পাঠাই না। যেকোনো সময় আনসাবস্ক্রাইব করুন।
                    </p>
                </form>

                <!-- Success Message -->
                <div v-if="showSuccess" class="success-banner">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>সফলভাবে সাবস্ক্রাইব হয়েছে! আপনার ইমেইল চেক করুন।</span>
                </div>

                <!-- Error Message -->
                <div v-if="errorMessage" class="error-banner">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ errorMessage }}</span>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const formData = ref({
    email: ''
});

const isSubmitting = ref(false);
const showSuccess = ref(false);
const errorMessage = ref('');

const handleSubmit = async () => {
    if (isSubmitting.value) return;
    
    isSubmitting.value = true;
    errorMessage.value = '';
    
    try {
        const response = await axios.post('/newsletter/subscribe', {
            email: formData.value.email
        });
        
        if (response.data.success) {
            showSuccess.value = true;
            formData.value.email = '';
            
            // Hide success message after 5 seconds
            setTimeout(() => {
                showSuccess.value = false;
            }, 5000);
        }
    } catch (error) {
        console.error('Newsletter subscription error:', error);
        
        if (error.response?.data?.message) {
            errorMessage.value = error.response.data.message;
        } else {
            errorMessage.value = 'সাবস্ক্রিপশনে সমস্যা হয়েছে। পুনরায় চেষ্টা করুন।';
        }
        
        // Hide error message after 5 seconds
        setTimeout(() => {
            errorMessage.value = '';
        }, 5000);
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<style scoped>
.newsletter-section {
    padding: 50px 0;
    background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
    position: relative;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

.newsletter-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 40px;
    position: relative;
}

/* Header Section */
.newsletter-header {
    display: flex;
    align-items: center;
    gap: 20px;
    flex: 1;
}

.newsletter-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.newsletter-icon svg {
    width: 32px;
    height: 32px;
    color: white;
}

.newsletter-text {
    flex: 1;
}

.newsletter-title {
    font-size: 24px;
    font-weight: 800;
    color: white;
    margin: 0 0 6px 0;
    line-height: 1.2;
}

.newsletter-subtitle {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
}

/* Form Section */
.subscribe-form {
    flex: 1;
    max-width: 600px;
}

.form-fields {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
}

.email-input {
    flex: 1;
    padding: 16px 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 10px;
    font-size: 15px;
    background: rgba(255, 255, 255, 0.95);
    transition: all 0.3s;
    font-family: inherit;
}

.email-input:focus {
    outline: none;
    border-color: white;
    background: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.email-input::placeholder {
    color: #95a5a6;
}

.submit-btn {
    padding: 16px 32px;
    background: #1e272e;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.submit-btn:hover:not(:disabled) {
    background: #0f1419;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.spinner {
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.form-note {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.85);
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 0;
}

.form-note svg {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
}

/* Success Banner */
.success-banner {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    color: #27ae60;
    padding: 20px 30px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    font-weight: 600;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    animation: slideDown 0.3s ease-out;
    z-index: 10;
}

.success-banner svg {
    width: 28px;
    height: 28px;
    flex-shrink: 0;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translate(-50%, -60%);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}

/* Error Banner */
.error-banner {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #e74c3c;
    color: white;
    padding: 18px 32px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    font-weight: 600;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    animation: slideDown 0.3s ease-out;
    z-index: 10;
}

.error-banner svg {
    width: 28px;
    height: 28px;
    flex-shrink: 0;
}

/* Responsive Design */
@media (max-width: 992px) {
    .newsletter-wrapper {
        flex-direction: column;
        text-align: center;
    }
    
    .newsletter-header {
        flex-direction: column;
        text-align: center;
    }
    
    .subscribe-form {
        width: 100%;
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .newsletter-section {
        padding: 40px 0;
    }
    
    .newsletter-title {
        font-size: 20px;
    }
    
    .newsletter-subtitle {
        font-size: 13px;
    }
    
    .newsletter-icon {
        width: 50px;
        height: 50px;
    }
    
    .newsletter-icon svg {
        width: 26px;
        height: 26px;
    }
    
    .form-fields {
        flex-direction: column;
    }
    
    .submit-btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .newsletter-title {
        font-size: 18px;
    }
    
    .email-input {
        padding: 14px 16px;
        font-size: 14px;
    }
    
    .submit-btn {
        padding: 14px 24px;
        font-size: 14px;
    }
}
</style>
