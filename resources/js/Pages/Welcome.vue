<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Ticker from '@/Components/Ticker.vue';
import Navbar from '@/Components/Navbar.vue';
import Notification from '@/Components/Notification.vue';
import HeroSlider from '@/Components/HeroSlider.vue';
import Products from '@/Components/Products.vue';
import AdvertBanner from '@/Components/AdvertBanner.vue';
import Features from '@/Components/Features.vue';
import Categories from '@/Components/Categories.vue';
import AdvertBanner2 from '@/Components/AdvertBanner2.vue';
import Testimonials from '@/Components/Testimonials.vue';
import Newsletter from '@/Components/Newsletter.vue';
import Footer from '@/Components/Footer.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    featuredProducts: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="পাহাড়ি পণ্য - বান্দরবান ও খাগড়াছড়ির খাঁটি পণ্য" />
    
    <!-- Green Ticker at the top -->
    <Ticker />
    
    <!-- Advanced Navbar -->
    <Navbar />

    <!-- Global Notifications -->
    <Notification />
    
    <!-- Hero Slider Section -->
    <HeroSlider />
    
    <!-- Featured Products Section -->
    <Products :products="featuredProducts" />
    
    <!-- Advertisement Banners -->
    <AdvertBanner />
    
    <!-- Why Choose Us Features -->
    <Features />
    
    <!-- Product Categories -->
    <Categories :categories="categories" />
    
    <!-- Second Advertisement Banners -->
    <AdvertBanner2 />
    
    <!-- Customer Testimonials -->
    <Testimonials />
    
    <!-- Newsletter Subscription -->
    <Newsletter />
    
    <!-- Footer -->
    <Footer />
</template>
