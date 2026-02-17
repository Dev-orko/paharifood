<template>
    <div class="hero-slider">
        <div class="slides-container">
            <!-- Slide 1: Emotion & Nature Focused -->
            <div 
                v-show="currentSlide === 0" 
                class="slide"
                :style="{ backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&h=1080&fit=crop')` }"
            >
                <div class="slide-content">
                    <h1 class="headline fade-in">পাহাড়ের সতেজতা, এখন আপনার দরজায়</h1>
                    <p class="subheadline fade-in-delay">
                        বান্দরবান ও খাগড়াছড়ির দুর্গম পাহাড় থেকে সরাসরি সংগ্রহ করা ১০০% কেমিক্যালমুক্ত ও জুম চাষের খাঁটি পণ্য। প্রকৃতির আসল স্বাদ উপভোগ করুন।
                    </p>
                    <button class="cta-btn fade-in-delay-2">শপ ভিজিট করুন</button>
                </div>
            </div>

            <!-- Slide 2: Trust & Purity Focused -->
            <div 
                v-show="currentSlide === 1" 
                class="slide"
                :style="{ backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1464820453369-31d2c0b651af?w=1920&h=1080&fit=crop')` }"
            >
                <div class="slide-content">
                    <h1 class="headline fade-in">সুস্থতায় প্রকৃতির ছোঁয়া</h1>
                    <p class="subheadline fade-in-delay">
                        ভেজালের ভিড়ে আমরা দিচ্ছি পাহাড়ি জুমের চাল, খাঁটি মধু, ঘি এবং মশলার নিশ্চয়তা। আপনার পরিবারের সুস্বাস্থ্য আমাদের অগ্রাধিকার।
                    </p>
                    <button class="cta-btn fade-in-delay-2">আমাদের কালেকশন দেখুন</button>
                </div>
            </div>

            <!-- Slide 3: Specific Product Highlight -->
            <div 
                v-show="currentSlide === 2" 
                class="slide"
                :style="{ backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1586281380117-5a60ae2050cc?w=1920&h=1080&fit=crop')` }"
            >
                <div class="slide-content">
                    <h1 class="headline fade-in">জুমের খাঁটি স্বাদ - পাহাড় থেকে সরাসরি</h1>
                    <p class="subheadline fade-in-delay">
                        প্রিমিয়াম কোয়ালিটি বিন্নি চাল, কানি চাল, জুমের হলুদ এবং চাক ভাঙা মধু এখন এক ক্লিকেই আপনার কাছে।
                    </p>
                    <button class="cta-btn fade-in-delay-2">অফারগুলো দেখুন</button>
                </div>
            </div>

            <!-- Slide 4: Minimalist -->
            <div 
                v-show="currentSlide === 3" 
                class="slide"
                :style="{ backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1920&h=1080&fit=crop')` }"
            >
                <div class="slide-content">
                    <h1 class="headline fade-in minimalist">খাঁটি পাহাড়ি স্বাদ</h1>
                    <p class="subheadline fade-in-delay minimalist">
                        ১০০% অর্গানিক | ন্যাচারাল | পাহাড়ি পণ্য
                    </p>
                    <button class="cta-btn fade-in-delay-2">এখনই কিনুন</button>
                </div>
            </div>
        </div>

        <!-- Navigation Dots -->
        <div class="slider-dots">
            <button 
                v-for="(slide, index) in 4" 
                :key="index"
                @click="goToSlide(index)"
                :class="['dot', { active: currentSlide === index }]"
                :aria-label="`Go to slide ${index + 1}`"
            ></button>
        </div>

        <!-- Arrow Navigation -->
        <button @click="previousSlide" class="slider-arrow prev" aria-label="Previous slide">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button @click="nextSlide" class="slider-arrow next" aria-label="Next slide">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const currentSlide = ref(0);
let autoplayInterval = null;

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % 4;
};

const previousSlide = () => {
    currentSlide.value = currentSlide.value === 0 ? 3 : currentSlide.value - 1;
};

const goToSlide = (index) => {
    currentSlide.value = index;
};

const startAutoplay = () => {
    autoplayInterval = setInterval(() => {
        nextSlide();
    }, 5000); // Change slide every 5 seconds
};

const stopAutoplay = () => {
    if (autoplayInterval) {
        clearInterval(autoplayInterval);
    }
};

onMounted(() => {
    startAutoplay();
});

onUnmounted(() => {
    stopAutoplay();
});
</script>

<style scoped>
.hero-slider {
    position: relative;
    width: 100%;
    height: 600px;
    overflow: hidden;
}

.slides-container {
    width: 100%;
    height: 100%;
    position: relative;
}

.slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.5s ease-in-out;
}

.slide-content {
    text-align: center;
    max-width: 900px;
    padding: 0 20px;
    color: white;
}

.headline {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
    line-height: 1.2;
}

.headline.minimalist {
    font-size: 4rem;
    letter-spacing: 2px;
}

.subheadline {
    font-size: 1.3rem;
    font-weight: 400;
    margin-bottom: 2.5rem;
    line-height: 1.8;
    text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.7);
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.subheadline.minimalist {
    font-size: 1.5rem;
    font-weight: 500;
    letter-spacing: 1px;
}

.cta-btn {
    background-color: #27ae60;
    color: white;
    font-size: 1.2rem;
    font-weight: 600;
    padding: 16px 40px;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4);
    text-transform: none;
}

.cta-btn:hover {
    background-color: #229954;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(39, 174, 96, 0.6);
}

/* Navigation Dots */
.slider-dots {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 12px;
    z-index: 10;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    border: 2px solid white;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0;
}

.dot:hover {
    background-color: rgba(255, 255, 255, 0.8);
    transform: scale(1.2);
}

.dot.active {
    background-color: #27ae60;
    border-color: #27ae60;
    width: 35px;
    border-radius: 10px;
}

/* Arrow Navigation */
.slider-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(255, 255, 255, 0.3);
    border: none;
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.slider-arrow:hover {
    background-color: rgba(39, 174, 96, 0.9);
    transform: translateY(-50%) scale(1.1);
}

.slider-arrow.prev {
    left: 30px;
}

.slider-arrow.next {
    right: 30px;
}

.slider-arrow svg {
    width: 24px;
    height: 24px;
}

/* Animations */
.fade-in {
    animation: fadeInUp 0.8s ease-out;
}

.fade-in-delay {
    animation: fadeInUp 1s ease-out 0.3s both;
}

.fade-in-delay-2 {
    animation: fadeInUp 1.2s ease-out 0.6s both;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .hero-slider {
        height: 500px;
    }

    .headline {
        font-size: 2rem;
    }

    .headline.minimalist {
        font-size: 2.5rem;
    }

    .subheadline {
        font-size: 1rem;
        margin-bottom: 2rem;
    }

    .subheadline.minimalist {
        font-size: 1.1rem;
    }

    .cta-btn {
        font-size: 1rem;
        padding: 12px 30px;
    }

    .slider-arrow {
        width: 40px;
        height: 40px;
    }

    .slider-arrow.prev {
        left: 15px;
    }

    .slider-arrow.next {
        right: 15px;
    }

    .slider-arrow svg {
        width: 20px;
        height: 20px;
    }
}

@media (max-width: 480px) {
    .hero-slider {
        height: 400px;
    }

    .headline {
        font-size: 1.5rem;
    }

    .subheadline {
        font-size: 0.9rem;
    }

    .slider-dots {
        bottom: 20px;
        gap: 8px;
    }

    .dot {
        width: 10px;
        height: 10px;
    }

    .dot.active {
        width: 25px;
    }
}
</style>
