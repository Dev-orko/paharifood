<template>
    <div class="bg-gray-50">
        <Navbar />
        <Notification />
        
        <div class="container mx-auto px-4 py-8">
            <!-- Search Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    অনুসন্ধান ফলাফল
                </h1>
                <p class="text-gray-600">
                    <span v-if="query">"{{ query }}" এর জন্য {{ products.length }} টি পণ্য পাওয়া গেছে</span>
                    <span v-else>সব পণ্য</span>
                </p>
            </div>

            <!-- Products Grid -->
            <div v-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300"
                >
                    <!-- Product Image -->
                    <Link :href="`/product/${product.slug}`" class="block">
                        <img
                            :src="product.image"
                            :alt="product.name_bn"
                            class="w-full h-64 object-cover"
                        />
                    </Link>

                    <!-- Product Info -->
                    <div class="p-4">
                        <!-- Product Name -->
                        <Link
                            :href="`/product/${product.slug}`"
                            class="block text-lg font-semibold text-gray-800 hover:text-green-600 mb-2 line-clamp-2"
                        >
                            {{ product.name_bn }}
                        </Link>

                        <!-- Category -->
                        <p v-if="product.category" class="text-sm text-gray-500 mb-2">
                            {{ product.category.name_bn }}
                        </p>

                        <!-- Rating -->
                        <div class="flex items-center mb-2">
                            <div class="flex">
                                <svg
                                    v-for="star in 5"
                                    :key="star"
                                    class="w-4 h-4"
                                    :class="star <= Math.round(product.rating) ? 'text-yellow-400' : 'text-gray-300'"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-600 ml-2">({{ product.reviews_count }})</span>
                        </div>

                        <!-- Price -->
                        <div class="mb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-2xl font-bold text-green-600">৳{{ product.price }}</span>
                                <span v-if="product.original_price && product.original_price > product.price" class="text-sm text-gray-500 line-through">
                                    ৳{{ product.original_price }}
                                </span>
                            </div>
                            <span v-if="product.original_price && product.original_price > product.price" class="text-xs text-red-600 font-semibold">
                                {{ Math.round(((product.original_price - product.price) / product.original_price) * 100) }}% ছাড়
                            </span>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-2">
                            <button
                                @click="addToCart(product.id)"
                                class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-semibold"
                            >
                                কার্টে যোগ করুন
                            </button>
                            <button
                                @click="toggleWishlist(product.id)"
                                class="bg-gray-100 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Stock Badge -->
                        <div v-if="product.stock <= 0" class="mt-2">
                            <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                                স্টক নেই
                            </span>
                        </div>
                        <div v-else-if="product.stock < 10" class="mt-2">
                            <span class="inline-block bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded">
                                মাত্র {{ product.stock }} টি বাকি
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-16">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h3 class="text-2xl font-semibold text-gray-800 mb-2">কোন পণ্য পাওয়া যায়নি</h3>
                <p class="text-gray-600 mb-6">
                    <span v-if="query">"{{ query }}" এর জন্য কোন পণ্য খুঁজে পাওয়া যায়নি</span>
                    <span v-else>এখনো কোন পণ্য যোগ করা হয়নি</span>
                </p>
                <Link href="/" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    হোম পেজে ফিরে যান
                </Link>
            </div>
        </div>

        <Footer />
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Notification from '@/Components/Notification.vue';
import Footer from '@/Components/Footer.vue';
import { useCart } from '@/composables/useCart';
import { useWishlist } from '@/composables/useWishlist';

// Props
const props = defineProps({
    products: {
        type: Array,
        required: true,
    },
    query: {
        type: String,
        default: '',
    },
});

// Composables
const { addToCart } = useCart();
const { toggleWishlist } = useWishlist();
</script>
