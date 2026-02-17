<script setup>
import { ref, computed, watch } from 'vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import Notification from '../Components/Notification.vue';
import { useNotification } from '../composables/useNotification';

const props = defineProps({
    user: Object,
    orders: Array,
});

const { notifications, notify, removeNotification } = useNotification();

const page = usePage();
const activeTab = ref('profile');

// Flash message watcher
watch(
    () => page.props.flash?.success,
    (msg) => {
        if (msg) notify({ type: 'success', message: msg });
    }
);

// ====== AVATAR ======
const avatarPreview = ref(props.user.avatar_url);
const avatarFile = ref(null);
const avatarInput = ref(null);

// Keep preview in sync after server-side upload/remove
watch(() => props.user.avatar_url, (newUrl) => {
    avatarPreview.value = newUrl;
    avatarFile.value = null;
});

const triggerAvatarUpload = () => avatarInput.value?.click();

const handleAvatarChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        notify({ type: 'error', message: 'ছবির সাইজ ২MB এর কম হতে হবে' });
        return;
    }
    avatarFile.value = file;
    avatarPreview.value = URL.createObjectURL(file);
};

const uploadAvatar = () => {
    if (!avatarFile.value) return;
    const formData = new FormData();
    formData.append('avatar', avatarFile.value);
    router.post('/account/avatar', formData, {
        forceFormData: true,
        onSuccess: () => {
            avatarFile.value = null;
            notify({ type: 'success', message: 'প্রোফাইল ছবি আপডেট হয়েছে!' });
        },
        onError: () => notify({ type: 'error', message: 'ছবি আপলোড করতে সমস্যা হয়েছে' }),
    });
};

const removeAvatar = () => {
    if (!props.user.avatar_url && !avatarPreview.value) return;
    router.delete('/account/avatar', {
        onSuccess: () => {
            avatarPreview.value = null;
            avatarFile.value = null;
            notify({ type: 'success', message: 'প্রোফাইল ছবি মুছে ফেলা হয়েছে' });
        },
    });
};

// ====== PERSONAL INFO ======
const personalForm = useForm({
    name: props.user.name || '',
    email: props.user.email || '',
    phone: props.user.phone || '',
    date_of_birth: props.user.date_of_birth ? props.user.date_of_birth.split('T')[0] : '',
    gender: props.user.gender || '',
});

const savePersonal = () => {
    personalForm.put('/account/personal', {
        onSuccess: () => notify({ type: 'success', message: 'ব্যক্তিগত তথ্য আপডেট হয়েছে!' }),
    });
};

// ====== SHIPPING ADDRESS ======
const shippingForm = useForm({
    shipping_address: props.user.shipping_address || '',
    shipping_city: props.user.shipping_city || '',
    shipping_postal_code: props.user.shipping_postal_code || '',
    shipping_division: props.user.shipping_division || '',
});

const saveShipping = () => {
    shippingForm.put('/account/shipping', {
        onSuccess: () => notify({ type: 'success', message: 'ডেলিভারি ঠিকানা আপডেট হয়েছে!' }),
    });
};

// ====== BILLING ADDRESS ======
const billingForm = useForm({
    billing_address: props.user.billing_address || '',
    billing_city: props.user.billing_city || '',
    billing_postal_code: props.user.billing_postal_code || '',
    billing_division: props.user.billing_division || '',
});

const useSameAsShipping = () => {
    billingForm.billing_address = shippingForm.shipping_address;
    billingForm.billing_city = shippingForm.shipping_city;
    billingForm.billing_postal_code = shippingForm.shipping_postal_code;
    billingForm.billing_division = shippingForm.shipping_division;
};

const saveBilling = () => {
    billingForm.put('/account/billing', {
        onSuccess: () => notify({ type: 'success', message: 'বিলিং ঠিকানা আপডেট হয়েছে!' }),
    });
};

// ====== PASSWORD ======
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});
const showCurrentPw = ref(false);
const showNewPw = ref(false);
const showConfirmPw = ref(false);

const passwordStrength = computed(() => {
    const pw = passwordForm.password;
    if (!pw) return { level: 0, text: '', color: '' };
    let score = 0;
    if (pw.length >= 8) score++;
    if (/[A-Z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;
    const map = [
        { level: 0, text: '', color: '' },
        { level: 1, text: 'দুর্বল', color: '#e74c3c' },
        { level: 2, text: 'মোটামুটি', color: '#f39c12' },
        { level: 3, text: 'ভালো', color: '#3498db' },
        { level: 4, text: 'শক্তিশালী', color: '#27ae60' },
    ];
    return map[score];
});

const savePassword = () => {
    passwordForm.put('/account/password', {
        onSuccess: () => {
            passwordForm.reset();
            notify({ type: 'success', message: 'পাসওয়ার্ড পরিবর্তন হয়েছে!' });
        },
    });
};

// ====== NOTIFICATIONS / PREFERENCES ======
const notifForm = useForm({
    email_notifications: props.user.email_notifications ?? true,
    sms_notifications: props.user.sms_notifications ?? false,
    order_updates: props.user.order_updates ?? true,
    promotional_emails: props.user.promotional_emails ?? false,
    preferred_payment: props.user.preferred_payment || 'cod',
});

const saveNotifications = () => {
    notifForm.put('/account/notifications', {
        onSuccess: () => notify({ type: 'success', message: 'সেটিংস আপডেট হয়েছে!' }),
    });
};

// ====== DELETE ACCOUNT ======
const showDeleteModal = ref(false);
const deleteForm = useForm({ password: '' });

const deleteAccount = () => {
    deleteForm.delete('/account', {
        onSuccess: () => {},
    });
};

// ====== ORDERS ======
const getStatusBadge = (status) => {
    const map = {
        pending: { text: 'অপেক্ষমাণ', class: 'badge-pending' },
        processing: { text: 'প্রক্রিয়াধীন', class: 'badge-processing' },
        shipped: { text: 'শিপিং হয়েছে', class: 'badge-shipped' },
        delivered: { text: 'ডেলিভারি হয়েছে', class: 'badge-delivered' },
        cancelled: { text: 'বাতিল', class: 'badge-cancelled' },
    };
    return map[status] || { text: status, class: 'badge-pending' };
};

const getPaymentBadge = (status) => {
    const map = {
        pending: { text: 'অপেক্ষমাণ', class: 'pay-pending' },
        paid: { text: 'পরিশোধিত', class: 'pay-paid' },
        failed: { text: 'ব্যর্থ', class: 'pay-failed' },
    };
    return map[status] || { text: status, class: 'pay-pending' };
};

// User initials for avatar fallback
const userInitials = computed(() => {
    if (!props.user?.name) return '?';
    const parts = props.user.name.trim().split(/\s+/);
    if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    return parts[0].substring(0, 2).toUpperCase();
});

// Tabs config
const tabs = [
    { key: 'profile', icon: '👤', label: 'প্রোফাইল' },
    { key: 'addresses', icon: '📍', label: 'ঠিকানা' },
    { key: 'orders', icon: '📦', label: 'অর্ডার' },
    { key: 'security', icon: '🔒', label: 'নিরাপত্তা' },
    { key: 'preferences', icon: '⚙️', label: 'সেটিংস' },
];

const divisions = [
    'ঢাকা', 'চট্টগ্রাম', 'রাজশাহী', 'খুলনা',
    'বরিশাল', 'সিলেট', 'রংপুর', 'ময়মনসিংহ',
];
</script>

<template>
    <Head title="অ্যাকাউন্ট সেটিংস" />
    <Navbar />

    <div class="acct-page">
        <div class="acct-container">
            <!-- Page Header -->
            <div class="acct-header">
                <div class="acct-header-inner">
                    <div class="acct-header-left">
                        <h1>অ্যাকাউন্ট সেটিংস</h1>
                        <p>আপনার প্রোফাইল এবং পছন্দসমূহ পরিচালনা করুন</p>
                    </div>
                    <a href="/" class="back-to-shop">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        দোকানে ফিরুন
                    </a>
                </div>
            </div>

            <div class="acct-layout">
                <!-- Sidebar -->
                <aside class="acct-sidebar">
                    <!-- Profile Summary -->
                    <div class="sidebar-profile">
                        <div class="sidebar-avatar">
                            <img v-if="avatarPreview" :src="avatarPreview" alt="Avatar" />
                            <span v-else class="sidebar-initials">{{ userInitials }}</span>
                        </div>
                        <h3>{{ user.name }}</h3>
                        <p>{{ user.email }}</p>
                        <span class="member-badge">🏔️ পাহাড়ি গ্রাহক</span>
                    </div>

                    <!-- Navigation Tabs -->
                    <nav class="sidebar-nav">
                        <button
                            v-for="tab in tabs"
                            :key="tab.key"
                            @click="activeTab = tab.key"
                            class="sidebar-tab"
                            :class="{ 'sidebar-tab--active': activeTab === tab.key }"
                        >
                            <span class="tab-icon">{{ tab.icon }}</span>
                            <span class="tab-label">{{ tab.label }}</span>
                            <svg class="tab-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </nav>
                </aside>

                <!-- Main Content -->
                <main class="acct-main">
                    <!-- ====== PROFILE TAB ====== -->
                    <div v-if="activeTab === 'profile'" class="tab-content">
                        <!-- Avatar Section -->
                        <section class="settings-card">
                            <div class="card-header">
                                <h2>প্রোফাইল ছবি</h2>
                                <p>আপনার প্রোফাইল ছবি পরিবর্তন করুন</p>
                            </div>
                            <div class="avatar-section">
                                <div class="avatar-display">
                                    <div class="avatar-large" @click="triggerAvatarUpload">
                                        <img v-if="avatarPreview" :src="avatarPreview" alt="Avatar" />
                                        <span v-else class="avatar-initials-lg">{{ userInitials }}</span>
                                        <div class="avatar-overlay">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            <span>ছবি পরিবর্তন</span>
                                        </div>
                                    </div>
                                    <input ref="avatarInput" type="file" accept="image/*" @change="handleAvatarChange" class="hidden-input" />
                                </div>
                                <div class="avatar-info">
                                    <p class="avatar-hint">JPG, PNG বা WebP। সর্বোচ্চ ২MB।</p>
                                    <div class="avatar-actions">
                                        <button @click="uploadAvatar" class="btn btn-primary btn-sm" :disabled="!avatarFile">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                            আপলোড করুন
                                        </button>
                                        <button @click="removeAvatar" class="btn btn-outline-danger btn-sm" :disabled="!avatarPreview">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            মুছে ফেলুন
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Personal Info Section -->
                        <section class="settings-card">
                            <div class="card-header">
                                <h2>ব্যক্তিগত তথ্য</h2>
                                <p>আপনার মৌলিক তথ্য আপডেট করুন</p>
                            </div>
                            <form @submit.prevent="savePersonal" class="form-grid">
                                <div class="form-group">
                                    <label>পুরো নাম</label>
                                    <input v-model="personalForm.name" type="text" class="form-input" placeholder="আপনার নাম" />
                                    <span v-if="personalForm.errors.name" class="form-error">{{ personalForm.errors.name }}</span>
                                </div>
                                <div class="form-group">
                                    <label>ইমেইল</label>
                                    <input v-model="personalForm.email" type="email" class="form-input" placeholder="example@mail.com" />
                                    <span v-if="personalForm.errors.email" class="form-error">{{ personalForm.errors.email }}</span>
                                </div>
                                <div class="form-group">
                                    <label>ফোন নম্বর</label>
                                    <input v-model="personalForm.phone" type="tel" class="form-input" placeholder="০১XXXXXXXXX" />
                                    <span v-if="personalForm.errors.phone" class="form-error">{{ personalForm.errors.phone }}</span>
                                </div>
                                <div class="form-group">
                                    <label>জন্ম তারিখ</label>
                                    <input v-model="personalForm.date_of_birth" type="date" class="form-input" />
                                    <span v-if="personalForm.errors.date_of_birth" class="form-error">{{ personalForm.errors.date_of_birth }}</span>
                                </div>
                                <div class="form-group form-group-full">
                                    <label>লিঙ্গ</label>
                                    <div class="gender-options">
                                        <label class="radio-card" :class="{ 'radio-card--active': personalForm.gender === 'male' }">
                                            <input type="radio" v-model="personalForm.gender" value="male" />
                                            <span>👨 পুরুষ</span>
                                        </label>
                                        <label class="radio-card" :class="{ 'radio-card--active': personalForm.gender === 'female' }">
                                            <input type="radio" v-model="personalForm.gender" value="female" />
                                            <span>👩 মহিলা</span>
                                        </label>
                                        <label class="radio-card" :class="{ 'radio-card--active': personalForm.gender === 'other' }">
                                            <input type="radio" v-model="personalForm.gender" value="other" />
                                            <span>🧑 অন্যান্য</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" :disabled="personalForm.processing">
                                        {{ personalForm.processing ? 'সংরক্ষণ হচ্ছে...' : 'পরিবর্তন সংরক্ষণ করুন' }}
                                    </button>
                                </div>
                            </form>
                        </section>
                    </div>

                    <!-- ====== ADDRESSES TAB ====== -->
                    <div v-if="activeTab === 'addresses'" class="tab-content">
                        <!-- Shipping Address -->
                        <section class="settings-card">
                            <div class="card-header">
                                <h2>📦 ডেলিভারি ঠিকানা</h2>
                                <p>আপনার পণ্য কোথায় ডেলিভারি হবে</p>
                            </div>
                            <form @submit.prevent="saveShipping" class="form-grid">
                                <div class="form-group form-group-full">
                                    <label>সম্পূর্ণ ঠিকানা</label>
                                    <textarea v-model="shippingForm.shipping_address" class="form-input form-textarea" placeholder="বাড়ি নং, রোড, এলাকা..." rows="3"></textarea>
                                    <span v-if="shippingForm.errors.shipping_address" class="form-error">{{ shippingForm.errors.shipping_address }}</span>
                                </div>
                                <div class="form-group">
                                    <label>শহর / উপজেলা</label>
                                    <input v-model="shippingForm.shipping_city" type="text" class="form-input" placeholder="ঢাকা" />
                                </div>
                                <div class="form-group">
                                    <label>পোস্টাল কোড</label>
                                    <input v-model="shippingForm.shipping_postal_code" type="text" class="form-input" placeholder="১২০৫" />
                                </div>
                                <div class="form-group form-group-full">
                                    <label>বিভাগ</label>
                                    <div class="division-grid">
                                        <label v-for="div in divisions" :key="div" class="radio-card radio-card-sm" :class="{ 'radio-card--active': shippingForm.shipping_division === div }">
                                            <input type="radio" v-model="shippingForm.shipping_division" :value="div" />
                                            <span>{{ div }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" :disabled="shippingForm.processing">
                                        {{ shippingForm.processing ? 'সংরক্ষণ হচ্ছে...' : 'ডেলিভারি ঠিকানা সংরক্ষণ' }}
                                    </button>
                                </div>
                            </form>
                        </section>

                        <!-- Billing Address -->
                        <section class="settings-card">
                            <div class="card-header">
                                <div>
                                    <h2>💳 বিলিং ঠিকানা</h2>
                                    <p>পেমেন্ট এবং রসিদের জন্য ঠিকানা</p>
                                </div>
                                <button @click="useSameAsShipping" class="btn btn-outline btn-sm">
                                    ডেলিভারি ঠিকানা ব্যবহার করুন
                                </button>
                            </div>
                            <form @submit.prevent="saveBilling" class="form-grid">
                                <div class="form-group form-group-full">
                                    <label>সম্পূর্ণ ঠিকানা</label>
                                    <textarea v-model="billingForm.billing_address" class="form-input form-textarea" placeholder="বাড়ি নং, রোড, এলাকা..." rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>শহর / উপজেলা</label>
                                    <input v-model="billingForm.billing_city" type="text" class="form-input" placeholder="ঢাকা" />
                                </div>
                                <div class="form-group">
                                    <label>পোস্টাল কোড</label>
                                    <input v-model="billingForm.billing_postal_code" type="text" class="form-input" placeholder="১২০৫" />
                                </div>
                                <div class="form-group form-group-full">
                                    <label>বিভাগ</label>
                                    <div class="division-grid">
                                        <label v-for="div in divisions" :key="div" class="radio-card radio-card-sm" :class="{ 'radio-card--active': billingForm.billing_division === div }">
                                            <input type="radio" v-model="billingForm.billing_division" :value="div" />
                                            <span>{{ div }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" :disabled="billingForm.processing">
                                        {{ billingForm.processing ? 'সংরক্ষণ হচ্ছে...' : 'বিলিং ঠিকানা সংরক্ষণ' }}
                                    </button>
                                </div>
                            </form>
                        </section>
                    </div>

                    <!-- ====== ORDERS TAB ====== -->
                    <div v-if="activeTab === 'orders'" class="tab-content">
                        <section class="settings-card">
                            <div class="card-header">
                                <h2>আমার অর্ডারসমূহ</h2>
                                <p>আপনার সাম্প্রতিক অর্ডারের তালিকা</p>
                            </div>

                            <div v-if="orders && orders.length > 0" class="orders-list">
                                <div v-for="order in orders" :key="order.id" class="order-card">
                                    <div class="order-card-header">
                                        <div class="order-info">
                                            <span class="order-number">#{{ order.order_number }}</span>
                                            <span class="order-date">{{ new Date(order.created_at).toLocaleDateString('bn-BD') }}</span>
                                        </div>
                                        <div class="order-badges">
                                            <span class="order-badge" :class="getStatusBadge(order.order_status).class">
                                                {{ getStatusBadge(order.order_status).text }}
                                            </span>
                                            <span class="order-badge" :class="getPaymentBadge(order.payment_status).class">
                                                {{ getPaymentBadge(order.payment_status).text }}
                                            </span>
                                        </div>
                                    </div>
                                    <div v-if="order.items && order.items.length" class="order-items-preview">
                                        <div v-for="item in order.items.slice(0, 3)" :key="item.id" class="order-item-mini">
                                            <img v-if="item.product?.image" :src="'/storage/' + item.product.image" :alt="item.product?.name" />
                                            <div v-else class="item-placeholder">📦</div>
                                            <div class="order-item-info">
                                                <span>{{ item.product?.name || 'পণ্য' }}</span>
                                                <small>x{{ item.quantity }}</small>
                                            </div>
                                        </div>
                                        <span v-if="order.items.length > 3" class="more-items">+{{ order.items.length - 3 }} আরো</span>
                                    </div>
                                    <div class="order-card-footer">
                                        <span class="order-total">মোট: ৳{{ parseFloat(order.total).toFixed(2) }}</span>
                                        <span class="order-payment">{{ order.payment_method.toUpperCase() }}</span>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="empty-state">
                                <div class="empty-icon">📦</div>
                                <h3>এখনো কোনো অর্ডার নেই</h3>
                                <p>আপনার প্রথম অর্ডার করুন!</p>
                                <a href="/" class="btn btn-primary">কেনাকাটা শুরু করুন</a>
                            </div>
                        </section>
                    </div>

                    <!-- ====== SECURITY TAB ====== -->
                    <div v-if="activeTab === 'security'" class="tab-content">
                        <!-- Change Password -->
                        <section class="settings-card">
                            <div class="card-header">
                                <h2>🔒 পাসওয়ার্ড পরিবর্তন</h2>
                                <p>আপনার অ্যাকাউন্টের নিরাপত্তা নিশ্চিত করুন</p>
                            </div>
                            <form @submit.prevent="savePassword" class="form-grid form-grid-single">
                                <div class="form-group">
                                    <label>বর্তমান পাসওয়ার্ড</label>
                                    <div class="input-with-toggle">
                                        <input v-model="passwordForm.current_password" :type="showCurrentPw ? 'text' : 'password'" class="form-input" placeholder="আপনার বর্তমান পাসওয়ার্ড" />
                                        <button type="button" @click="showCurrentPw = !showCurrentPw" class="toggle-pw">
                                            <svg v-if="!showCurrentPw" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                        </button>
                                    </div>
                                    <span v-if="passwordForm.errors.current_password" class="form-error">{{ passwordForm.errors.current_password }}</span>
                                </div>
                                <div class="form-group">
                                    <label>নতুন পাসওয়ার্ড</label>
                                    <div class="input-with-toggle">
                                        <input v-model="passwordForm.password" :type="showNewPw ? 'text' : 'password'" class="form-input" placeholder="নতুন পাসওয়ার্ড লিখুন" />
                                        <button type="button" @click="showNewPw = !showNewPw" class="toggle-pw">
                                            <svg v-if="!showNewPw" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                        </button>
                                    </div>
                                    <!-- Password Strength -->
                                    <div v-if="passwordForm.password" class="pw-strength">
                                        <div class="pw-strength-bar">
                                            <div class="pw-strength-fill" :style="{ width: (passwordStrength.level * 25) + '%', background: passwordStrength.color }"></div>
                                        </div>
                                        <span class="pw-strength-text" :style="{ color: passwordStrength.color }">{{ passwordStrength.text }}</span>
                                    </div>
                                    <span v-if="passwordForm.errors.password" class="form-error">{{ passwordForm.errors.password }}</span>
                                </div>
                                <div class="form-group">
                                    <label>পাসওয়ার্ড নিশ্চিত করুন</label>
                                    <div class="input-with-toggle">
                                        <input v-model="passwordForm.password_confirmation" :type="showConfirmPw ? 'text' : 'password'" class="form-input" placeholder="আবার পাসওয়ার্ড লিখুন" />
                                        <button type="button" @click="showConfirmPw = !showConfirmPw" class="toggle-pw">
                                            <svg v-if="!showConfirmPw" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                        </button>
                                    </div>
                                    <div v-if="passwordForm.password && passwordForm.password_confirmation && passwordForm.password !== passwordForm.password_confirmation" class="form-error">পাসওয়ার্ড মিলছে না</div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" :disabled="passwordForm.processing || !passwordForm.current_password || !passwordForm.password">
                                        {{ passwordForm.processing ? 'পরিবর্তন হচ্ছে...' : 'পাসওয়ার্ড পরিবর্তন করুন' }}
                                    </button>
                                </div>
                            </form>
                        </section>

                        <!-- Delete Account -->
                        <section class="settings-card danger-zone">
                            <div class="card-header">
                                <h2>⚠️ অ্যাকাউন্ট মুছে ফেলুন</h2>
                                <p>একবার মুছে ফেললে ফিরে পাওয়া যাবে না</p>
                            </div>
                            <div class="danger-content">
                                <p>আপনার অ্যাকাউন্ট মুছে ফেলা হলে সকল ডেটা, অর্ডারের ইতিহাস এবং ব্যক্তিগত তথ্য স্থায়ীভাবে মুছে যাবে।</p>
                                <button @click="showDeleteModal = true" class="btn btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    অ্যাকাউন্ট মুছে ফেলুন
                                </button>
                            </div>

                            <!-- Delete Modal -->
                            <Teleport to="body">
                                <div v-if="showDeleteModal" class="modal-overlay" @click.self="showDeleteModal = false">
                                    <div class="modal-box">
                                        <div class="modal-header">
                                            <h3>⚠️ অ্যাকাউন্ট মুছে ফেলার নিশ্চিতকরণ</h3>
                                        </div>
                                        <div class="modal-body">
                                            <p>এই কাজটি অপরিবর্তনীয়। আপনার সকল তথ্য মুছে যাবে।</p>
                                            <div class="form-group">
                                                <label>নিশ্চিত করতে পাসওয়ার্ড দিন</label>
                                                <input v-model="deleteForm.password" type="password" class="form-input" placeholder="আপনার পাসওয়ার্ড" />
                                                <span v-if="deleteForm.errors.password" class="form-error">{{ deleteForm.errors.password }}</span>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button @click="showDeleteModal = false" class="btn btn-outline">বাতিল</button>
                                            <button @click="deleteAccount" class="btn btn-danger" :disabled="!deleteForm.password || deleteForm.processing">
                                                {{ deleteForm.processing ? 'মুছে ফেলা হচ্ছে...' : 'হ্যাঁ, মুছে ফেলুন' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </Teleport>
                        </section>
                    </div>

                    <!-- ====== PREFERENCES TAB ====== -->
                    <div v-if="activeTab === 'preferences'" class="tab-content">
                        <section class="settings-card">
                            <div class="card-header">
                                <h2>🔔 নোটিফিকেশন পছন্দ</h2>
                                <p>কোন কোন বিষয়ে জানতে চান নির্বাচন করুন</p>
                            </div>
                            <form @submit.prevent="saveNotifications">
                                <div class="toggle-list">
                                    <div class="toggle-item">
                                        <div class="toggle-info">
                                            <span class="toggle-title">📧 ইমেইল নোটিফিকেশন</span>
                                            <span class="toggle-desc">গুরুত্বপূর্ণ আপডেট ইমেইলে পাঠানো হবে</span>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" v-model="notifForm.email_notifications" />
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="toggle-item">
                                        <div class="toggle-info">
                                            <span class="toggle-title">📱 SMS নোটিফিকেশন</span>
                                            <span class="toggle-desc">অর্ডার আপডেট SMS এ পাঠানো হবে</span>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" v-model="notifForm.sms_notifications" />
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="toggle-item">
                                        <div class="toggle-info">
                                            <span class="toggle-title">📦 অর্ডার আপডেট</span>
                                            <span class="toggle-desc">অর্ডার স্ট্যাটাস পরিবর্তনের সময় জানানো হবে</span>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" v-model="notifForm.order_updates" />
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="toggle-item">
                                        <div class="toggle-info">
                                            <span class="toggle-title">🎉 প্রমোশনাল ইমেইল</span>
                                            <span class="toggle-desc">অফার ও ডিসকাউন্ট এর খবর পান</span>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" v-model="notifForm.promotional_emails" />
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="card-separator"></div>

                                <div class="card-header" style="padding-top: 0;">
                                    <h2>💰 পছন্দের পেমেন্ট পদ্ধতি</h2>
                                    <p>ডিফল্ট পেমেন্ট মাধ্যম নির্বাচন করুন</p>
                                </div>
                                <div class="payment-options">
                                    <label class="payment-card" :class="{ 'payment-card--active': notifForm.preferred_payment === 'cod' }">
                                        <input type="radio" v-model="notifForm.preferred_payment" value="cod" />
                                        <span class="payment-icon">💵</span>
                                        <span class="payment-name">ক্যাশ অন ডেলিভারি</span>
                                    </label>
                                    <label class="payment-card" :class="{ 'payment-card--active': notifForm.preferred_payment === 'bkash' }">
                                        <input type="radio" v-model="notifForm.preferred_payment" value="bkash" />
                                        <span class="payment-icon">📱</span>
                                        <span class="payment-name">বিকাশ</span>
                                    </label>
                                    <label class="payment-card" :class="{ 'payment-card--active': notifForm.preferred_payment === 'nagad' }">
                                        <input type="radio" v-model="notifForm.preferred_payment" value="nagad" />
                                        <span class="payment-icon">📲</span>
                                        <span class="payment-name">নগদ</span>
                                    </label>
                                    <label class="payment-card" :class="{ 'payment-card--active': notifForm.preferred_payment === 'rocket' }">
                                        <input type="radio" v-model="notifForm.preferred_payment" value="rocket" />
                                        <span class="payment-icon">🚀</span>
                                        <span class="payment-name">রকেট</span>
                                    </label>
                                    <label class="payment-card" :class="{ 'payment-card--active': notifForm.preferred_payment === 'card' }">
                                        <input type="radio" v-model="notifForm.preferred_payment" value="card" />
                                        <span class="payment-icon">💳</span>
                                        <span class="payment-name">কার্ড</span>
                                    </label>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" :disabled="notifForm.processing">
                                        {{ notifForm.processing ? 'সংরক্ষণ হচ্ছে...' : 'সেটিংস সংরক্ষণ করুন' }}
                                    </button>
                                </div>
                            </form>
                        </section>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <Footer />

    <Teleport to="body">
        <Notification
            v-for="notif in notifications"
            :key="notif.id"
            :type="notif.type"
            :message="notif.message"
            @close="removeNotification(notif.id)"
        />
    </Teleport>
</template>

<style scoped>
/* ================================================
   ACCOUNT SETTINGS PAGE — পাহাড়ি স্বাদ
   ================================================ */
.acct-page {
    background: #f5f7f6;
    min-height: 100vh;
    padding-bottom: 60px;
}

.acct-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Page Header */
.acct-header {
    padding: 30px 0 20px;
}

.acct-header-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.acct-header h1 {
    font-size: 28px;
    font-weight: 800;
    color: #1e272e;
    margin: 0;
}

.acct-header p {
    font-size: 14px;
    color: #888;
    margin: 4px 0 0;
}

.back-to-shop {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: white;
    border-radius: 10px;
    text-decoration: none;
    color: #27ae60;
    font-weight: 600;
    font-size: 14px;
    border: 1.5px solid #e0e0e0;
    transition: all 0.25s ease;
}

.back-to-shop svg {
    width: 18px;
    height: 18px;
}

.back-to-shop:hover {
    border-color: #27ae60;
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.12);
}

/* Layout */
.acct-layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 24px;
    align-items: start;
}

/* Sidebar */
.acct-sidebar {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    position: sticky;
    top: 180px;
}

.sidebar-profile {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 28px 20px 20px;
    background: linear-gradient(135deg, #f0faf5 0%, #e8f5e9 100%);
    border-bottom: 1px solid #e8ece9;
}

.sidebar-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(145deg, #27ae60, #1a7a42);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid white;
    box-shadow: 0 4px 16px rgba(39,174,96,0.25);
    overflow: hidden;
    margin-bottom: 12px;
}

.sidebar-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.sidebar-initials {
    color: white;
    font-size: 24px;
    font-weight: 800;
}

.sidebar-profile h3 {
    font-size: 16px;
    font-weight: 700;
    color: #1e272e;
    margin: 0;
    text-align: center;
}

.sidebar-profile p {
    font-size: 12px;
    color: #888;
    margin: 2px 0 0;
}

.member-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-top: 10px;
    padding: 4px 14px;
    background: white;
    border: 1px solid #c8e6c9;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    color: #1e8449;
}

/* Sidebar Nav */
.sidebar-nav {
    padding: 8px;
}

.sidebar-tab {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 12px 14px;
    border: none;
    background: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    color: #555;
    transition: all 0.2s ease;
    text-align: left;
}

.sidebar-tab:hover {
    background: #f5faf7;
    color: #1e272e;
}

.sidebar-tab--active {
    background: linear-gradient(135deg, #e8f5e9, #f0faf5);
    color: #27ae60;
    font-weight: 700;
}

.tab-icon {
    font-size: 18px;
    width: 24px;
    text-align: center;
}

.tab-label {
    flex: 1;
}

.tab-arrow {
    width: 16px;
    height: 16px;
    color: #ccc;
    opacity: 0;
    transition: all 0.2s;
}

.sidebar-tab:hover .tab-arrow,
.sidebar-tab--active .tab-arrow {
    opacity: 1;
    color: #27ae60;
}

/* Main Content */
.acct-main {
    min-width: 0;
}

.tab-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
    animation: tabFadeIn 0.3s ease;
}

@keyframes tabFadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Settings Card */
.settings-card {
    background: white;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}

.card-header h2 {
    font-size: 18px;
    font-weight: 700;
    color: #1e272e;
    margin: 0;
}

.card-header p {
    font-size: 13px;
    color: #999;
    margin: 3px 0 0;
}

.card-separator {
    height: 1px;
    background: linear-gradient(90deg, transparent, #e8e8e8 20%, #e8e8e8 80%, transparent);
    margin: 24px 0;
}

/* Avatar Section */
.avatar-section {
    display: flex;
    align-items: center;
    gap: 28px;
}

.avatar-large {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: linear-gradient(145deg, #27ae60, #1a7a42);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    border: 4px solid #e8f5e9;
    box-shadow: 0 6px 24px rgba(39,174,96,0.2);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.avatar-large:hover {
    transform: scale(1.03);
    box-shadow: 0 8px 32px rgba(39,174,96,0.3);
}

.avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-initials-lg {
    color: white;
    font-size: 38px;
    font-weight: 800;
}

.avatar-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    gap: 4px;
}

.avatar-large:hover .avatar-overlay {
    opacity: 1;
}

.avatar-overlay svg {
    width: 24px;
    height: 24px;
    color: white;
}

.avatar-overlay span {
    font-size: 11px;
    color: white;
    font-weight: 600;
}

.hidden-input {
    display: none;
}

.avatar-info {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.avatar-hint {
    font-size: 13px;
    color: #999;
    margin: 0;
}

.avatar-actions {
    display: flex;
    gap: 10px;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-grid-single {
    grid-template-columns: 1fr;
    max-width: 480px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-group-full {
    grid-column: 1 / -1;
}

.form-group label {
    font-size: 13px;
    font-weight: 600;
    color: #555;
}

.form-input {
    padding: 11px 16px;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    font-size: 14px;
    color: #1e272e;
    background: #fafafa;
    transition: all 0.25s ease;
    outline: none;
    width: 100%;
    box-sizing: border-box;
}

.form-input:focus {
    border-color: #27ae60;
    background: white;
    box-shadow: 0 0 0 3px rgba(39,174,96,0.08);
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
    font-family: inherit;
}

.form-error {
    font-size: 12px;
    color: #e74c3c;
    font-weight: 500;
}

.form-actions {
    grid-column: 1 / -1;
    display: flex;
    justify-content: flex-end;
    padding-top: 8px;
}

/* Input with Toggle (Password) */
.input-with-toggle {
    position: relative;
}

.input-with-toggle .form-input {
    padding-right: 48px;
}

.toggle-pw {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    color: #999;
    transition: color 0.2s;
}

.toggle-pw:hover {
    color: #27ae60;
}

.toggle-pw svg {
    width: 20px;
    height: 20px;
}

/* Password Strength */
.pw-strength {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 4px;
}

.pw-strength-bar {
    flex: 1;
    height: 4px;
    background: #eee;
    border-radius: 4px;
    overflow: hidden;
}

.pw-strength-fill {
    height: 100%;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.pw-strength-text {
    font-size: 12px;
    font-weight: 600;
    min-width: 60px;
}

/* Gender / Radio Cards */
.gender-options,
.division-grid {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.radio-card {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.25s ease;
    font-size: 14px;
    font-weight: 500;
    color: #555;
    background: #fafafa;
}

.radio-card input {
    display: none;
}

.radio-card:hover {
    border-color: #a3d9b8;
    background: #f5faf7;
}

.radio-card--active {
    border-color: #27ae60;
    background: #e8f5e9;
    color: #1e8449;
    font-weight: 600;
}

.radio-card-sm {
    padding: 8px 14px;
    font-size: 13px;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
}

.btn svg {
    width: 18px;
    height: 18px;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 13px;
}

.btn-sm svg {
    width: 16px;
    height: 16px;
}

.btn-primary {
    background: linear-gradient(135deg, #27ae60, #1e8449);
    color: white;
    box-shadow: 0 4px 12px rgba(39,174,96,0.2);
}

.btn-primary:hover:not(:disabled) {
    box-shadow: 0 6px 20px rgba(39,174,96,0.3);
    transform: translateY(-1px);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-outline {
    background: white;
    border: 1.5px solid #e0e0e0;
    color: #555;
}

.btn-outline:hover {
    border-color: #27ae60;
    color: #27ae60;
}

.btn-outline-danger {
    background: white;
    border: 1.5px solid #f5c6cb;
    color: #e74c3c;
}

.btn-outline-danger:hover:not(:disabled) {
    background: #fff5f5;
    border-color: #e74c3c;
}

.btn-outline-danger:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-danger {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    box-shadow: 0 4px 12px rgba(231,76,60,0.2);
}

.btn-danger:hover:not(:disabled) {
    box-shadow: 0 6px 20px rgba(231,76,60,0.3);
    transform: translateY(-1px);
}

.btn-danger:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Danger Zone */
.danger-zone {
    border: 1.5px solid #f5c6cb;
}

.danger-zone .card-header h2 {
    color: #e74c3c;
}

.danger-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.danger-content p {
    font-size: 13px;
    color: #888;
    margin: 0;
    max-width: 500px;
}

/* Toggle / Switch */
.toggle-list {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.toggle-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    border-radius: 10px;
    transition: background 0.2s;
}

.toggle-item:hover {
    background: #fafcfb;
}

.toggle-info {
    display: flex;
    flex-direction: column;
}

.toggle-title {
    font-size: 14px;
    font-weight: 600;
    color: #1e272e;
}

.toggle-desc {
    font-size: 12px;
    color: #999;
    margin-top: 2px;
}

/* Toggle Switch */
.switch {
    position: relative;
    width: 48px;
    height: 26px;
    flex-shrink: 0;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background: #ddd;
    border-radius: 26px;
    transition: all 0.3s;
}

.slider::before {
    content: '';
    position: absolute;
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background: white;
    border-radius: 50%;
    transition: all 0.3s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.15);
}

.switch input:checked + .slider {
    background: #27ae60;
}

.switch input:checked + .slider::before {
    transform: translateX(22px);
}

/* Payment Options */
.payment-options {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 12px;
    margin-bottom: 16px;
}

.payment-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    padding: 16px 12px;
    border: 1.5px solid #e0e0e0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.25s ease;
    background: #fafafa;
    text-align: center;
}

.payment-card input {
    display: none;
}

.payment-card:hover {
    border-color: #a3d9b8;
}

.payment-card--active {
    border-color: #27ae60;
    background: #e8f5e9;
    box-shadow: 0 2px 8px rgba(39,174,96,0.1);
}

.payment-icon {
    font-size: 28px;
}

.payment-name {
    font-size: 13px;
    font-weight: 600;
    color: #555;
}

.payment-card--active .payment-name {
    color: #1e8449;
}

/* Orders */
.orders-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.order-card {
    border: 1.5px solid #eee;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.25s ease;
}

.order-card:hover {
    border-color: #d5e8dc;
    box-shadow: 0 4px 16px rgba(0,0,0,0.04);
}

.order-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 18px;
    background: #fafcfb;
    border-bottom: 1px solid #f0f0f0;
}

.order-info {
    display: flex;
    flex-direction: column;
}

.order-number {
    font-size: 14px;
    font-weight: 700;
    color: #1e272e;
}

.order-date {
    font-size: 12px;
    color: #999;
    margin-top: 2px;
}

.order-badges {
    display: flex;
    gap: 8px;
}

.order-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.badge-pending { background: #fff3cd; color: #856404; }
.badge-processing { background: #cce5ff; color: #004085; }
.badge-shipped { background: #d4edda; color: #155724; }
.badge-delivered { background: #d4edda; color: #155724; }
.badge-cancelled { background: #f8d7da; color: #721c24; }

.pay-pending { background: #fff3cd; color: #856404; }
.pay-paid { background: #d4edda; color: #155724; }
.pay-failed { background: #f8d7da; color: #721c24; }

.order-items-preview {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 18px;
    overflow-x: auto;
}

.order-item-mini {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.order-item-mini img,
.item-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.order-item-info {
    display: flex;
    flex-direction: column;
}

.order-item-info span {
    font-size: 13px;
    font-weight: 500;
    color: #1e272e;
    max-width: 120px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.order-item-info small {
    font-size: 11px;
    color: #999;
}

.more-items {
    font-size: 12px;
    color: #27ae60;
    font-weight: 600;
    white-space: nowrap;
}

.order-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 18px;
    background: #fafcfb;
    border-top: 1px solid #f0f0f0;
}

.order-total {
    font-size: 15px;
    font-weight: 700;
    color: #27ae60;
}

.order-payment {
    font-size: 12px;
    font-weight: 600;
    color: #888;
    padding: 3px 10px;
    background: #f0f0f0;
    border-radius: 6px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 50px 20px;
}

.empty-icon {
    font-size: 56px;
    margin-bottom: 16px;
}

.empty-state h3 {
    font-size: 18px;
    font-weight: 700;
    color: #1e272e;
    margin: 0 0 6px;
}

.empty-state p {
    font-size: 14px;
    color: #999;
    margin: 0 0 20px;
}

/* Modal */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-box {
    background: white;
    border-radius: 16px;
    width: 100%;
    max-width: 440px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    animation: modalIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f0f0f0;
}

.modal-header h3 {
    font-size: 16px;
    font-weight: 700;
    color: #e74c3c;
    margin: 0;
}

.modal-body {
    padding: 20px 24px;
}

.modal-body p {
    font-size: 14px;
    color: #666;
    margin: 0 0 16px;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #f0f0f0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* ===== Responsive ===== */
@media (max-width: 900px) {
    .acct-layout {
        grid-template-columns: 1fr;
    }

    .acct-sidebar {
        position: static;
    }

    .sidebar-nav {
        display: flex;
        overflow-x: auto;
        padding: 8px;
        gap: 4px;
    }

    .sidebar-tab {
        white-space: nowrap;
        flex-shrink: 0;
        padding: 10px 16px;
    }

    .tab-arrow {
        display: none;
    }
}

@media (max-width: 640px) {
    .acct-header-inner {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .avatar-section {
        flex-direction: column;
        text-align: center;
    }

    .avatar-actions {
        justify-content: center;
    }

    .danger-content {
        flex-direction: column;
        text-align: center;
    }

    .settings-card {
        padding: 20px 16px;
    }

    .payment-options {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
