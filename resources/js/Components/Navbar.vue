<template>
    <header class="header-wrapper" :class="{ 'scrolled': isScrolled }">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="container">
                <div class="top-bar-content">
                    <div class="top-bar-left">
                        <span class="hotline">
                            📞 হটলাইন: ০১৭xxxxxxxx <span class="hours">(সকাল ১০টা - রাত ১০টা)</span>
                        </span>
                    </div>
                    <div class="top-bar-right">
                        <a href="#" class="track-order">🚚 অর্ডার ট্র্যাক করুন</a>
                        <span class="divider">|</span>
                        <div class="language-switcher">
                            <button class="lang-btn active">🇧🇩 বাংলা</button>
                            <span>/</span>
                            <button class="lang-btn">English</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <div class="main-nav">
            <div class="container">
                <div class="main-nav-content">
                    <!-- Logo -->
                    <div class="logo-section">
                        <a href="/" class="logo">
                            <div class="logo-icon">🏔️</div>
                            <div class="logo-text">
                                <span class="brand-name">পাহাড়ি স্বাদ</span>
                                <span class="brand-tagline">Hill Taste</span>
                            </div>
                        </a>
                    </div>

                    <!-- Search Bar -->
                    <div class="search-section">
                        <form @submit.prevent="handleSearch" class="search-wrapper">
                            <input 
                                type="text" 
                                class="search-input" 
                                placeholder="পাহাড়ি মধু, জুমের চাল বা মশলা খুঁজুন..."
                                v-model="searchQuery"
                            />
                            <button type="submit" class="search-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    <!-- User Actions -->
                    <div class="user-actions">
                        <!-- Mobile Menu Button -->
                        <button @click="toggleMobileMenu" class="mobile-menu-btn">
                            <svg v-if="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <a href="/wishlist" class="action-btn minim-btn wishlist-btn" aria-label="Wishlist">
                            <div class="icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span v-if="wishlistCount > 0" class="minibadge">{{ wishlistCount }}</span>
                            </div>
                        </a>

                        <a href="/cart" class="action-btn minim-btn cart-btn" aria-label="Cart">
                            <div class="icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span v-if="cartCount > 0" class="minibadge">{{ cartCount }}</span>
                            </div>
                            <div class="cart-summary">
                                <span v-if="cartTotal && cartTotal > 0">ঝুড়ি • ৳{{ cartTotal.toFixed(2) }}</span>
                                <span v-else>ঝুড়ি • খালি</span>
                            </div>
                        </a>

                        <!-- Authenticated User Dropdown -->
                        <div v-if="user" class="ud-wrapper" ref="userDropdownRef">
                            <button @click="toggleUserDropdown" class="ud-trigger" :class="{ 'ud-trigger--open': userDropdownOpen }">
                                <div class="ud-avatar-ring">
                                    <div class="ud-avatar">
                                        <img v-if="user.avatar_url" :src="user.avatar_url" alt="Avatar" class="ud-avatar-img" />
                                        <span v-else>{{ userInitials }}</span>
                                    </div>
                                    <span class="ud-online-dot"></span>
                                </div>
                                <div class="ud-trigger-text">
                                    <span class="ud-name">{{ user.name.split(' ')[0] }}</span>
                                    <span class="ud-greeting">পাহাড়ি গ্রাহক</span>
                                </div>
                                <svg class="ud-chevron" :class="{ 'ud-chevron--up': userDropdownOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                            </button>

                            <Transition name="ud-panel">
                                <div v-if="userDropdownOpen" class="ud-dropdown">

                                    <!-- Header -->
                                    <div class="ud-head">
                                        <div class="ud-head-noise"></div>
                                        <div class="ud-head-orb ud-head-orb1"></div>
                                        <div class="ud-head-orb ud-head-orb2"></div>
                                        <div class="ud-head-avatar-wrap">
                                            <div class="ud-head-avatar">
                                                <img v-if="user.avatar_url" :src="user.avatar_url" alt="Avatar" class="ud-avatar-img" />
                                                <span v-else>{{ userInitials }}</span>
                                            </div>
                                            <span class="ud-head-dot"></span>
                                        </div>
                                        <div class="ud-head-info">
                                            <h4>{{ user.name }}</h4>
                                            <p>{{ user.email }}</p>
                                        </div>
                                        <div class="ud-head-stats">
                                            <a href="/cart" class="ud-chip">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                                <span>{{ cartCount }}</span>
                                                <em>ঝুড়ি</em>
                                            </a>
                                            <a href="/wishlist" class="ud-chip ud-chip--rose">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                                <span>{{ wishlistCount }}</span>
                                                <em>পছন্দ</em>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Menu -->
                                    <nav class="ud-menu">
                                        <a href="/account" class="ud-row">
                                            <span class="ud-row-icon" style="background:#e8f5e9;color:#27ae60">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            </span>
                                            <span class="ud-row-label">অ্যাকাউন্ট সেটিংস</span>
                                            <svg class="ud-row-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                                        </a>
                                        <a href="/cart" class="ud-row">
                                            <span class="ud-row-icon" style="background:#e3f2fd;color:#1976d2">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                            </span>
                                            <span class="ud-row-label">আমার ঝুড়ি</span>
                                            <span v-if="cartCount > 0" class="ud-row-count">{{ cartCount }}</span>
                                            <svg class="ud-row-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                                        </a>
                                        <a href="/wishlist" class="ud-row">
                                            <span class="ud-row-icon" style="background:#fce4ec;color:#e91e63">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                            </span>
                                            <span class="ud-row-label">পছন্দের তালিকা</span>
                                            <span v-if="wishlistCount > 0" class="ud-row-count ud-row-count--rose">{{ wishlistCount }}</span>
                                            <svg class="ud-row-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                                        </a>
                                    </nav>

                                    <!-- Footer / Logout -->
                                    <div class="ud-footer">
                                        <Link href="/logout" method="post" as="button" class="ud-logout">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                            লগ আউট
                                        </Link>
                                    </div>
                                </div>
                            </Transition>
                        </div>

                        <!-- Guest Login/Register -->
                        <div v-else>
                             <a href="/login" class="action-btn account-btn">
                                <div class="guest-avatar-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <span class="action-text">
                                    <span class="action-label">অ্যাকাউন্ট</span>
                                    <span class="action-sublabel">লগইন / রেজিস্টার</span>
                                </span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Bar -->
        <nav class="menu-bar">
            <div class="container">
                <ul class="menu-list">
                    <li class="menu-item">
                        <a href="/" class="menu-link active">🏠 হোম</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">সকল পণ্য</a>
                    </li>
                    <li class="menu-item has-dropdown">
                        <a href="#" class="menu-link">🍯 মধু ও ঘি</a>
                        <div class="dropdown">
                            <a href="#" class="dropdown-item">চাক ভাঙা মধু</a>
                            <a href="#" class="dropdown-item">খাঁটি ঘি</a>
                            <a href="#" class="dropdown-item">সরিষার তেল</a>
                        </div>
                    </li>
                    <li class="menu-item has-dropdown">
                        <a href="#" class="menu-link">🍚 পাহাড়ি চাল</a>
                        <div class="dropdown">
                            <a href="#" class="dropdown-item">বিন্নি চাল</a>
                            <a href="#" class="dropdown-item">কানি চাল</a>
                            <a href="#" class="dropdown-item">জুমের চাল</a>
                        </div>
                    </li>
                    <li class="menu-item has-dropdown">
                        <a href="#" class="menu-link">🌶️ মশলা ও আচার</a>
                        <div class="dropdown">
                            <a href="#" class="dropdown-item">জুমের হলুদ</a>
                            <a href="#" class="dropdown-item">শুকনা মরিচ</a>
                            <a href="#" class="dropdown-item">পাহাড়ি আচার</a>
                        </div>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">☕ চা ও কফি</a>
                    </li>
                    <li class="menu-item featured">
                        <a href="#" class="menu-link">🔥 অফার</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">📞 যোগাযোগ</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Mobile Menu Overlay -->
        <div v-if="mobileMenuOpen" class="mobile-menu-overlay" @click="toggleMobileMenu"></div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu" :class="{ 'mobile-menu-open': mobileMenuOpen }">
            <div class="mobile-menu-header">
                <h3>মেনু</h3>
                <button @click="toggleMobileMenu" class="mobile-close-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <ul class="mobile-menu-list">
                <li><a href="/" class="mobile-menu-link">🏠 হোম</a></li>
                <li><a href="#" class="mobile-menu-link">সকল পণ্য</a></li>
                <li><a href="#" class="mobile-menu-link">🍯 মধু ও ঘি</a></li>
                <li><a href="#" class="mobile-menu-link">🍚 পাহাড়ি চাল</a></li>
                <li><a href="#" class="mobile-menu-link">🌶️ মশলা ও আচার</a></li>
                <li><a href="#" class="mobile-menu-link">☕ চা ও কফি</a></li>
                <li><a href="#" class="mobile-menu-link featured">🔥 অফার</a></li>
                <li><a href="#" class="mobile-menu-link">📞 যোগাযোগ</a></li>
            </ul>
        </div>
    </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useCart } from '../composables/useCart';
import { useWishlist } from '../composables/useWishlist';
import { router, usePage, Link } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);

const userInitials = computed(() => {
    if (!user.value?.name) return '?';
    const parts = user.value.name.trim().split(/\s+/);
    if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    return parts[0].substring(0, 2).toUpperCase();
});

const searchQuery = ref('');
const wishlistCount = ref(0);
const cartCount = ref(0);
const cartTotal = ref(0.00);
const mobileMenuOpen = ref(false);
const isScrolled = ref(false);
const userDropdownOpen = ref(false);
const userDropdownRef = ref(null);

const toggleUserDropdown = () => {
    userDropdownOpen.value = !userDropdownOpen.value;
};

const closeUserDropdown = (e) => {
    if (userDropdownRef.value && !userDropdownRef.value.contains(e.target)) {
        userDropdownOpen.value = false;
    }
};

const handleScroll = () => {
    isScrolled.value = window.scrollY > 10;
};


const { getCartCount, getCartTotal } = useCart();
const { getWishlistCount } = useWishlist();

const handleSearch = () => {
    if (searchQuery.value.trim()) {
        router.get('/search', { q: searchQuery.value });
    }
};

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
    // Prevent body scroll when menu is open
    if (mobileMenuOpen.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
};

const updateCartCount = async () => {
    cartCount.value = await getCartCount();
    cartTotal.value = await getCartTotal();
};

const updateWishlistCount = async () => {
    wishlistCount.value = await getWishlistCount();
};

onMounted(async () => {
    // Fetch initial counts
    await updateCartCount();
    await updateWishlistCount();
    
    // Listen to scroll events
    window.addEventListener('scroll', handleScroll);

    // Listen to cart-updated events
    window.addEventListener('cart-updated', updateCartCount);
    
    // Listen to wishlist-updated events
    window.addEventListener('wishlist-updated', updateWishlistCount);

    // Close dropdown on outside click
    document.addEventListener('click', closeUserDropdown);
});

onUnmounted(() => {
    // Clean up event listeners
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('cart-updated', updateCartCount);
    window.removeEventListener('wishlist-updated', updateWishlistCount);
    document.removeEventListener('click', closeUserDropdown);
});
</script>

<style scoped>
.header-wrapper {
    width: 100%;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
    background: white;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Top Bar */
.top-bar {
    background: #1e272e;
    color: white;
    font-size: 13px;
    padding: 8px 0;
}

.top-bar-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.top-bar-left .hotline {
    font-weight: 500;
}

.top-bar-left .hours {
    opacity: 0.9;
    margin-left: 5px;
}

.top-bar-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

.track-order {
    color: white;
    text-decoration: none;
    transition: opacity 0.2s;
}

.track-order:hover {
    opacity: 0.7;
}

.divider {
    opacity: 0.5;
}

.language-switcher {
    display: flex;
    align-items: center;
    gap: 5px;
}

.lang-btn {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 13px;
    padding: 2px 8px;
    border-radius: 3px;
    transition: opacity 0.2s;
}

.lang-btn:hover {
    opacity: 0.7;
}

.lang-btn.active {
    background: rgba(255, 255, 255, 0.15);
    font-weight: 600;
}

/* Main Navigation */
.main-nav {
    background: white;
    padding: 10px 0;
    border-bottom: 1px solid #e8e8e8;
    transition: all 0.3s ease;
}

.scrolled .main-nav {
    padding: 5px 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.main-nav-content {
    display: flex;
    align-items: center;
    gap: 30px;
}

/* Logo */
.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    transition: opacity 0.2s;
}

.logo:hover {
    opacity: 0.8;
}

.logo-icon {
    font-size: 36px;
    transition: all 0.3s ease;
}

.scrolled .logo-icon {
    font-size: 32px;
}

.logo-text {
    display: flex;
    flex-direction: column;
}

.brand-name {
    font-size: 24px;
    font-weight: 800;
    color: #27ae60;
    line-height: 1;
}

.brand-tagline {
    font-size: 11px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Search Bar */
.search-section {
    flex: 1;
    max-width: 600px;
}

.search-wrapper {
    position: relative;
    display: flex;
}

.search-input {
    flex: 1;
    padding: 12px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 50px 0 0 50px;
    font-size: 14px;
    outline: none;
    transition: all 0.3s;
}

.scrolled .search-input {
    padding: 10px 20px;
}

.search-input:focus {
    border-color: #27ae60;
}

.search-btn {
    padding: 12px 25px;
    background: #27ae60;
    border: none;
    border-radius: 0 50px 50px 0;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
}

.scrolled .search-btn {
    padding: 10px 25px;
}

.search-btn:hover {
    background: #229954;
}

.search-btn svg {
    width: 20px;
    height: 20px;
}

/* User Actions */
.user-actions {
    display: flex;
    align-items: center;
    gap: 20px;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: #1e272e;
    transition: all 0.3s ease;
    padding: 8px 12px;
    border-radius: 50px;
}

.action-btn:hover {
    background-color: #f0f2f5;
}

.minim-btn .icon-wrap {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background-color: #f0f2f5;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.minim-btn:hover .icon-wrap {
    background-color: #e4e6e9;
    transform: scale(1.05);
}

.action-btn svg {
    width: 22px;
    height: 22px;
}

.guest-avatar-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border-radius: 50%;
}

.guest-avatar-icon svg {
    width: 20px;
    height: 20px;
    color: #27ae60;
}

.account-btn svg {
    width: 28px;
    height: 28px;
}

/* =============================================
   USER DROPDOWN v2 — Premium Glassmorphism Card
   ============================================= */

/* ============================================
   USER DROPDOWN — Premium Dark Design
   ============================================ */
.ud-wrapper {
    position: relative;
}

/* ── Trigger ── */
.ud-trigger {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 4px 10px 4px 4px;
    background: #ffffff;
    border: 1.5px solid #e0e0e0;
    border-radius: 50px;
    cursor: pointer;
    transition: border-color 0.25s, box-shadow 0.25s;
}

.ud-trigger:hover,
.ud-trigger--open {
    border-color: #27ae60;
    box-shadow: 0 0 0 3px rgba(39,174,96,0.1);
}

.ud-avatar-ring {
    position: relative;
    flex-shrink: 0;
}

.ud-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #27ae60, #0d5c2e);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border: 2px solid #e8f5e9;
}

.ud-avatar span {
    color: white;
    font-size: 12px;
    font-weight: 800;
}

.ud-avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.ud-online-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 9px;
    height: 9px;
    background: #22c55e;
    border: 2px solid white;
    border-radius: 50%;
}

.ud-trigger-text {
    display: flex;
    flex-direction: column;
    line-height: 1;
}

.ud-name {
    font-size: 13px;
    font-weight: 700;
    color: #111;
    max-width: 90px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ud-greeting {
    font-size: 10px;
    color: #27ae60;
    font-weight: 600;
    margin-top: 2px;
}

.ud-chevron {
    width: 14px;
    height: 14px;
    color: #bbb;
    transition: transform 0.3s ease, color 0.2s;
    flex-shrink: 0;
}

.ud-trigger:hover .ud-chevron { color: #27ae60; }
.ud-chevron--up { transform: rotate(180deg); }

/* ── Panel transition ── */
.ud-panel-enter-active {
    animation: udIn 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.ud-panel-leave-active {
    animation: udOut 0.18s ease-in forwards;
}
@keyframes udIn {
    from { opacity: 0; transform: translateY(-10px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0)     scale(1);    }
}
@keyframes udOut {
    from { opacity: 1; transform: translateY(0) scale(1);    }
    to   { opacity: 0; transform: translateY(-8px) scale(0.97); }
}

/* ── Dropdown Shell ── */
.ud-dropdown {
    position: absolute;
    top: calc(100% + 10px);
    right: -8px;
    width: 290px;
    background: #0e1a10;
    border-radius: 18px;
    box-shadow:
        0 0 0 1px rgba(255,255,255,0.06),
        0 8px 32px rgba(0,0,0,0.35),
        0 32px 72px rgba(0,0,0,0.2);
    z-index: 200;
    overflow: hidden;
}

/* ── Header ── */
.ud-head {
    position: relative;
    padding: 22px 20px 18px;
    background: linear-gradient(145deg, #0d5c2e 0%, #1a7a42 60%, #27ae60 100%);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
}

.ud-head-noise {
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
    pointer-events: none;
}

.ud-head-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}

.ud-head-orb1 {
    width: 120px;
    height: 120px;
    top: -40px;
    right: -30px;
    background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
}

.ud-head-orb2 {
    width: 80px;
    height: 80px;
    bottom: -20px;
    left: 30px;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
}

.ud-head-avatar-wrap {
    position: relative;
    z-index: 1;
}

.ud-head-avatar {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(255,255,255,0.25), rgba(255,255,255,0.1));
    border: 2.5px solid rgba(255,255,255,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    backdrop-filter: blur(4px);
}

.ud-head-avatar span {
    color: white;
    font-size: 20px;
    font-weight: 800;
}

.ud-head-avatar .ud-avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.ud-head-dot {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 11px;
    height: 11px;
    background: #4ade80;
    border: 2px solid #0d5c2e;
    border-radius: 50%;
    box-shadow: 0 0 6px #4ade80;
}

.ud-head-info {
    position: relative;
    z-index: 1;
}

.ud-head-info h4 {
    margin: 0;
    font-size: 15px;
    font-weight: 800;
    color: white;
    line-height: 1.2;
}

.ud-head-info p {
    margin: 3px 0 0;
    font-size: 11.5px;
    color: rgba(255,255,255,0.65);
    max-width: 220px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ud-head-stats {
    position: relative;
    z-index: 1;
    display: flex;
    gap: 8px;
}

.ud-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px 5px 8px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 20px;
    text-decoration: none;
    backdrop-filter: blur(8px);
    transition: background 0.2s;
}

.ud-chip:hover {
    background: rgba(255,255,255,0.25);
}

.ud-chip svg {
    width: 13px;
    height: 13px;
    color: rgba(255,255,255,0.85);
}

.ud-chip span {
    font-size: 13px;
    font-weight: 800;
    color: white;
    line-height: 1;
}

.ud-chip em {
    font-style: normal;
    font-size: 10px;
    color: rgba(255,255,255,0.65);
    font-weight: 500;
}

.ud-chip--rose {
    background: rgba(244,63,94,0.2);
    border-color: rgba(244,63,94,0.25);
}

.ud-chip--rose svg { color: #fda4af; }

/* ── Menu ── */
.ud-menu {
    padding: 8px;
    background: #0e1a10;
}

.ud-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.2s;
    cursor: pointer;
    border: none;
    background: transparent;
    width: 100%;
}

.ud-row:hover {
    background: rgba(255,255,255,0.05);
}

.ud-row-icon {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: transform 0.2s ease;
}

.ud-row:hover .ud-row-icon {
    transform: scale(1.1);
}

.ud-row-icon svg {
    width: 16px;
    height: 16px;
}

.ud-row-label {
    flex: 1;
    font-size: 13.5px;
    font-weight: 600;
    color: rgba(255,255,255,0.88);
}

.ud-row-count {
    font-size: 10px;
    font-weight: 700;
    background: #1976d2;
    color: white;
    border-radius: 20px;
    padding: 2px 7px;
    line-height: 1.4;
}

.ud-row-count--rose {
    background: #e91e63;
}

.ud-row-arrow {
    width: 13px;
    height: 13px;
    color: rgba(255,255,255,0.2);
    flex-shrink: 0;
    transition: transform 0.2s, color 0.2s;
}

.ud-row:hover .ud-row-arrow {
    transform: translateX(2px);
    color: rgba(255,255,255,0.55);
}

/* ── Footer / Logout ── */
.ud-footer {
    padding: 0 8px 8px;
    background: #0e1a10;
    border-top: 1px solid rgba(255,255,255,0.06);
    margin-top: 2px;
    padding-top: 6px;
}

.ud-logout {
    display: flex;
    align-items: center;
    gap: 9px;
    width: 100%;
    padding: 10px 12px;
    background: transparent;
    border: none;
    border-radius: 10px;
    color: rgba(248,113,113,0.85);
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}

.ud-logout svg {
    width: 17px;
    height: 17px;
    flex-shrink: 0;
    transition: transform 0.2s;
}

.ud-logout:hover {
    background: rgba(248,113,113,0.1);
    color: #f87171;
}

.ud-logout:hover svg {
    transform: translateX(2px);
}

.minim-btn svg {
    width: 20px;
    height: 20px;
    color: #333;
}

.minibadge {
    position: absolute;
    top: -2px;
    right: -4px;
    background-color: #e74c3c;
    color: white;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.cart-summary {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    white-space: nowrap;
    transition: color 0.3s ease;
}

.cart-btn:hover .cart-summary {
    color: #27ae60;
}

.wishlist-btn .icon-wrap {
    background-color: #fdf2f2;
}
.wishlist-btn:hover .icon-wrap {
    background-color: #fce8e6;
}
.wishlist-btn svg {
    color: #e74c3c;
}

.cart-btn .icon-wrap {
    background-color: #f0faf5;
}
.cart-btn:hover .icon-wrap {
    background-color: #e6f4ec;
}
.cart-btn svg {
    color: #27ae60;
}
.cart-btn .minibadge {
    background-color: #27ae60;
}

/* Menu Bar */
.menu-bar {
    background: #1e272e;
    padding: 0;
}

.menu-list {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 0;
    justify-content: center;
}

.menu-item {
    position: relative;
}

.menu-link {
    display: block;
    padding: 18px 24px;
    color: white;
    text-decoration: none;
    font-weight: 500;
    font-size: 15px;
    transition: border-bottom 0.2s;
    border-bottom: 2px solid transparent;
}

.menu-link:hover {
    border-bottom-color: rgba(255, 255, 255, 0.3);
}

.menu-link.active {
    border-bottom-color: #27ae60;
}

.menu-item.featured .menu-link {
    background: #e74c3c;
    color: white;
}

/* Dropdown */
.dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    min-width: 220px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    border-radius: 0;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s, visibility 0.2s;
    z-index: 100;
}

.has-dropdown:hover .dropdown {
    opacity: 1;
    visibility: visible;
}

.dropdown-item {
    display: block;
    padding: 14px 24px;
    color: #1e272e;
    text-decoration: none;
    transition: padding-left 0.2s;
    border-left: 2px solid transparent;
    font-size: 14px;
}

.dropdown-item:hover {
    border-left-color: #27ae60;
    padding-left: 28px;
}

/* Responsive */
@media (max-width: 1200px) {
    .search-section {
        max-width: 400px;
    }
}

/* Mobile Menu Button - Hidden by default, shown on mobile */
.mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    color: #1e272e;
}

.mobile-menu-btn svg {
    width: 28px;
    height: 28px;
}

/* Mobile Menu Overlay */
.mobile-menu-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1100;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Mobile Menu */
.mobile-menu {
    position: fixed;
    top: 0;
    left: -300px;
    width: 280px;
    height: 100vh;
    background: white;
    z-index: 1101;
    transition: left 0.3s ease;
    overflow-y: auto;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.mobile-menu-open {
    left: 0;
}

.mobile-menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e8e8e8;
    background: #27ae60;
    color: white;
}

.mobile-menu-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
}

.mobile-close-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    color: white;
}

.mobile-close-btn svg {
    width: 24px;
    height: 24px;
}

.mobile-menu-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.mobile-menu-link {
    display: block;
    padding: 16px 20px;
    color: #1e272e;
    text-decoration: none;
    font-weight: 500;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s, padding-left 0.2s;
}

.mobile-menu-link:hover {
    background: #f8f9fa;
    padding-left: 25px;
}

.mobile-menu-link.featured {
    background: #e74c3c;
    color: white;
}

.mobile-menu-link.featured:hover {
    background: #c0392b;
}

@media (max-width: 992px) {
    .main-nav-content {
        flex-wrap: wrap;
        gap: 15px;
    }

    .search-section {
        flex: 1 1 100%;
        max-width: 100%;
        order: 3;
    }

    .logo-section {
        flex: 1;
    }

    .menu-list {
        overflow-x: auto;
        flex-wrap: nowrap;
    }

    .menu-link {
        padding: 14px 16px;
        font-size: 14px;
        white-space: nowrap;
    }
}

@media (max-width: 768px) {
    .mobile-menu-btn {
        display: block;
    }

    .menu-bar {
        display: none;
    }

    .top-bar-content {
        flex-direction: column;
        gap: 8px;
        text-align: center;
    }

    .top-bar-right {
        justify-content: center;
    }

    .action-text {
        display: none;
    }

    .ud-trigger-text {
        display: none;
    }

    .ud-chevron-wrap {
        display: none;
    }

    .ud-trigger {
        padding: 4px;
        border-radius: 50%;
    }

    .ud-dropdown {
        right: -50px;
        width: 270px;
    }

    .ud-dropdown::before {
        right: 62px;
    }

    .action-btn {
        padding: 8px;
    }

    .logo-icon {
        font-size: 32px;
    }

    .brand-name {
        font-size: 20px;
    }

    .menu-bar {
        overflow-x: auto;
    }
}

@media (max-width: 480px) {
    .top-bar-left .hours {
        display: block;
        margin-left: 0;
        margin-top: 3px;
    }

    .user-actions {
        gap: 10px;
    }
}
</style>
