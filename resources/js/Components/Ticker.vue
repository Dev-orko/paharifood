<template>
    <div class="ticker-wrap">
        <div class="ticker">
            <div class="ticker-item" v-for="i in 2" :key="i">
                <span>📦 সারা বাংলাদেশে ক্যাশ অন ডেলিভারি (Cash on Delivery) সুবিধা। পণ্য হাতে পেয়ে পেমেন্ট করুন।</span>
                <span style="margin-left: 50px;">📞 অর্ডার করতে বা বিস্তারিত জানতে কল করুন আমাদের হটলাইন নাম্বারে: ০১৭xxxxxxxx</span>
                <span style="margin-left: 50px;">💰 bKash বিকাশ বা নগদে পেমেন্ট করলে পাচ্ছেন এক্সট্রা ৫% ক্যাশব্যাক!</span>
            </div>
        </div>
    </div>
</template>

<script setup>
// Green ticker component for Bangladesh e-commerce
</script>

<style scoped>
.ticker-wrap {
    width: 100%;
    background-color: #27ae60;
    padding: 12px 0;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 100;
}

.ticker {
    display: flex;
    animation: ticker 30s linear infinite;
    white-space: nowrap;
}

.ticker-item {
    color: white;
    font-size: 16px;
    font-weight: 500;
    padding: 0 50px;
    display: inline-block;
    line-height: 1.6;
}

@keyframes ticker {
    0% {
        transform: translate3d(0, 0, 0);
    }
    100% {
        transform: translate3d(-50%, 0, 0);
    }
}

/* Pause animation on hover */
.ticker-wrap:hover .ticker {
    animation-play-state: paused;
}

/* Responsive */
@media (max-width: 768px) {
    .ticker-item {
        font-size: 14px;
        padding: 0 30px;
    }
}
</style>
