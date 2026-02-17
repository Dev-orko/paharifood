<template>
    <section class="testimonials-section">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header">
                <h2 class="section-title">গ্রাহক পর্যালোচনা</h2>
                <div class="title-underline"></div>
                <p class="section-subtitle">আমাদের সন্তুষ্ট ক্রেতাদের মতামত</p>
            </div>

            <!-- Testimonials Slider -->
            <div class="testimonials-slider">
                <div class="testimonials-track" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
                    <!-- Slide 1 -->
                    <div class="testimonials-slide">
                        <div v-for="review in slides[0]" :key="review.id" class="testimonial-card">
                            <div class="quote-icon">❝</div>
                            <div class="rating">
                                <span v-for="star in 5" :key="star" class="star">★</span>
                            </div>
                            <p class="review-text">{{ review.text }}</p>
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">{{ review.name.charAt(0) }}</div>
                                <div class="reviewer-details">
                                    <h4 class="reviewer-name">{{ review.name }}</h4>
                                    <p class="reviewer-location">{{ review.location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="testimonials-slide">
                        <div v-for="review in slides[1]" :key="review.id" class="testimonial-card">
                            <div class="quote-icon">❝</div>
                            <div class="rating">
                                <span v-for="star in 5" :key="star" class="star">★</span>
                            </div>
                            <p class="review-text">{{ review.text }}</p>
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">{{ review.name.charAt(0) }}</div>
                                <div class="reviewer-details">
                                    <h4 class="reviewer-name">{{ review.name }}</h4>
                                    <p class="reviewer-location">{{ review.location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="testimonials-slide">
                        <div v-for="review in slides[2]" :key="review.id" class="testimonial-card">
                            <div class="quote-icon">❝</div>
                            <div class="rating">
                                <span v-for="star in 5" :key="star" class="star">★</span>
                            </div>
                            <p class="review-text">{{ review.text }}</p>
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">{{ review.name.charAt(0) }}</div>
                                <div class="reviewer-details">
                                    <h4 class="reviewer-name">{{ review.name }}</h4>
                                    <p class="reviewer-location">{{ review.location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 4 -->
                    <div class="testimonials-slide">
                        <div v-for="review in slides[3]" :key="review.id" class="testimonial-card">
                            <div class="quote-icon">❝</div>
                            <div class="rating">
                                <span v-for="star in 5" :key="star" class="star">★</span>
                            </div>
                            <p class="review-text">{{ review.text }}</p>
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">{{ review.name.charAt(0) }}</div>
                                <div class="reviewer-details">
                                    <h4 class="reviewer-name">{{ review.name }}</h4>
                                    <p class="reviewer-location">{{ review.location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Dots -->
                <div class="slider-dots">
                    <button
                        v-for="(slide, index) in 4"
                        :key="index"
                        class="dot"
                        :class="{ active: currentSlide === index }"
                        @click="goToSlide(index)"
                    ></button>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const currentSlide = ref(0);
let intervalId = null;

const slides = ref([
    // Slide 1 - 5 reviews
    [
        { id: 1, name: 'রহিম আহমেদ', location: 'ঢাকা', text: 'অসাধারণ মানের মধু পেয়েছি। একদম খাঁটি এবং প্রাকৃতিক। দাম অনুযায়ী পণ্যের মান খুবই ভালো। আবারও অর্ডার করবো।' },
        { id: 2, name: 'সালমা খাতুন', location: 'চট্টগ্রাম', text: 'পাহাড়ি হলুদ এবং মসলা সত্যিই দারুণ। রান্নায় সুগন্ধ এবং স্বাদ অনেক বেড়ে গেছে। ডেলিভারি ও দ্রুত পেয়েছি।' },
        { id: 3, name: 'করিম মিয়া', location: 'সিলেট', text: 'প্রথমবার অর্ডার করে খুবই সন্তুষ্ট। সব পণ্য তাজা এবং প্যাকেজিং দুর্দান্ত। গ্রাহক সেবা ও ভালো।' },
        { id: 4, name: 'নাজমা বেগম', location: 'রাজশাহী', text: 'জৈব পণ্যের মান অনেক ভালো। ঘরে বসে খাঁটি পাহাড়ি পণ্য পাওয়া যাচ্ছে, এটা সত্যিই চমৎকার সেবা।' },
        { id: 5, name: 'জাহিদ হাসান', location: 'খুলনা', text: 'বন্ধুর সুপারিশে অর্ডার করেছিলাম। সত্যিই মন ভরে গেছে। সবাইকে এই দোকান থেকে কেনার জন্য সুপারিশ করবো।' },
    ],
    // Slide 2 - 5 reviews
    [
        { id: 6, name: 'ফরিদা আক্তার', location: 'বরিশাল', text: 'ঘি খুবই সুন্দর এবং খাঁটি। এত বছর পর আসল ঘি এর স্বাদ পেলাম। পরিবারের সবাই খুব পছন্দ করেছে।' },
        { id: 7, name: 'আলম মিয়া', location: 'রংপুর', text: 'চাল এবং ডালের মান খুব ভালো। রান্না করলে ভালো সুগন্ধ বের হয়। দাম ও যুক্তিসংগত।' },
        { id: 8, name: 'রুমানা সুলতানা', location: 'ময়মনসিংহ', text: 'দ্রুত ডেলিভারি এবং ভালো প্যাকেজিং। সব পণ্য একদম নতুন এবং তাজা পেয়েছি। ধন্যবাদ।' },
        { id: 9, name: 'সাকিব হোসেন', location: 'নারায়ণগঞ্জ', text: 'মসলার মান দেখে মুগ্ধ হয়েছি। খাঁটি এবং প্রাকৃতিক। রান্নায় ব্যবহার করে চমৎকার ফল পেয়েছি।' },
        { id: 10, name: 'তাহমিনা আক্তার', location: 'যশোর', text: 'অনলাইনে পণ্য কিনে এত সন্তুষ্ট হয়েছি প্রথমবার। মান এবং সেবা দুটোই দুর্দান্ত।' },
    ],
    // Slide 3 - 5 reviews
    [
        { id: 11, name: 'হাবিব রহমান', location: 'কুমিল্লা', text: 'পাহাড়ি পণ্যের মান সত্যিই ভালো। মধু খুবই খাঁটি। পরিবারের সবার জন্য স্বাস্থ্যকর পছন্দ।' },
        { id: 12, name: 'শাহানা পারভীন', location: 'বগুড়া', text: 'চা এবং মসলা চমৎকার। প্রতিটি অর্ডারে ভালো পণ্য পাচ্ছি। আর কোথাও যাবো না।' },
        { id: 13, name: 'মোস্তফা কামাল', location: 'ফরিদপুর', text: 'খাদ্য পণ্যের মান অসাধারণ। প্রাকৃতিক এবং ভেজালমুক্ত। পরিবারের জন্য নিরাপদ।' },
        { id: 14, name: 'রেহানা সুলতানা', location: 'পাবনা', text: 'প্রথম অর্ডার থেকেই পছন্দ হয়ে গেছে। গ্রাহক সেবা ভালো, দাম যুক্তিসংগত।' },
        { id: 15, name: 'শফিকুল ইসলাম', location: 'দিনাজপুর', text: 'জৈব পণ্য সত্যিই মানসম্পন্ন। আমি নিয়মিত ক্রেতা হয়ে গেছি। সবাইকে সুপারিশ করি।' },
    ],
    // Slide 4 - 5 reviews
    [
        { id: 16, name: 'লাইলা নাসরিন', location: 'সাতক্ষীরা', text: 'দুগ্ধজাত পণ্য অনেক ভালো মানের। ঘি আর দই সত্যিই খাঁটি। দাম অনুযায়ী মান চমৎকার।' },
        { id: 17, name: 'আমিনুল ইসলাম', location: 'গাজীপুর', text: 'হলুদ এবং মরিচ গুঁড়া অনেক ভালো। রং এবং সুগন্ধ দুটোই দারুণ। নিয়মিত কিনছি।' },
        { id: 18, name: 'সুমাইয়া খানম', location: 'টাঙ্গাইল', text: 'প্যাকেজিং এবং ডেলিভারি খুবই ভালো। পণ্যের মান আশানুরূপ পেয়েছি। ধন্যবাদ।' },
        { id: 19, name: 'জামাল উদ্দিন', location: 'কুষ্টিয়া', text: 'পাহাড়ি পণ্য নিয়ে অনেক সন্দেহ ছিল। কিন্তু এই দোকান থেকে কিনে মন ভরে গেছে।' },
        { id: 20, name: 'নাসরিন সুলতানা', location: 'ব্রাহ্মণবাড়িয়া', text: 'সব ধরনের পণ্য ভালো মানের। দাম একটু বেশি মনে হলেও মান দেখে সন্তুষ্ট। চালিয়ে যান।' },
    ],
]);

const autoSlide = () => {
    intervalId = setInterval(() => {
        currentSlide.value = (currentSlide.value + 1) % 4;
    }, 5000);
};

const goToSlide = (index) => {
    currentSlide.value = index;
    clearInterval(intervalId);
    autoSlide();
};

onMounted(() => {
    autoSlide();
});

onUnmounted(() => {
    if (intervalId) {
        clearInterval(intervalId);
    }
});
</script>

<style scoped>
.testimonials-section {
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

/* Testimonials Slider */
.testimonials-slider {
    position: relative;
    overflow: hidden;
}

.testimonials-track {
    display: flex;
    transition: transform 0.6s ease-in-out;
}

.testimonials-slide {
    min-width: 100%;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 24px;
}

/* Testimonial Card */
.testimonial-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e8e8e8;
    position: relative;
}

.quote-icon {
    font-size: 48px;
    color: #27ae60;
    opacity: 0.2;
    position: absolute;
    top: 20px;
    left: 20px;
    line-height: 1;
}

.rating {
    display: flex;
    gap: 4px;
    margin-bottom: 16px;
}

.star {
    color: #f39c12;
    font-size: 16px;
}

.review-text {
    font-size: 14px;
    color: #2c3e50;
    line-height: 1.7;
    margin: 0 0 24px 0;
    min-height: 100px;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-top: 16px;
    border-top: 1px solid #f1f2f6;
}

.reviewer-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 700;
}

.reviewer-details {
    flex: 1;
}

.reviewer-name {
    font-size: 15px;
    font-weight: 700;
    color: #1e272e;
    margin: 0 0 4px 0;
}

.reviewer-location {
    font-size: 12px;
    color: #95a5a6;
    margin: 0;
}

/* Slider Dots */
.slider-dots {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 40px;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #ddd;
    border: none;
    cursor: pointer;
    padding: 0;
}

.dot.active {
    background: #27ae60;
    width: 32px;
    border-radius: 6px;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .testimonials-slide {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .testimonials-slide {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    
    .testimonials-section {
        padding: 50px 0;
    }
    
    .section-title {
        font-size: 32px;
    }
    
    .testimonial-card {
        padding: 20px;
    }
    
    .review-text {
        min-height: 80px;
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .testimonials-slide {
        grid-template-columns: 1fr;
    }
    
    .testimonials-section {
        padding: 40px 0;
    }
}
</style>
