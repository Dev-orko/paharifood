<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const showConfirm = ref(false);

const passwordStrength = computed(() => {
    const p = form.password;
    if (!p) return { level: 0, label: '', color: '' };
    let score = 0;
    if (p.length >= 8) score++;
    if (/[A-Z]/.test(p)) score++;
    if (/[0-9]/.test(p)) score++;
    if (/[^A-Za-z0-9]/.test(p)) score++;
    if (score <= 1) return { level: 1, label: 'দুর্বল', color: '#ef4444' };
    if (score === 2) return { level: 2, label: 'মোটামুটি', color: '#f59e0b' };
    if (score === 3) return { level: 3, label: 'ভালো', color: '#3b82f6' };
    return { level: 4, label: 'শক্তিশালী', color: '#10b981' };
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="রেজিস্টার — পাহাড়ি স্বাদ" />

        <div class="register-page">
            <div class="register-header">
                <h1 class="register-title">নতুন অ্যাকাউন্ট তৈরি করুন</h1>
                <p class="register-subtitle">পাহাড়ের খাঁটি পণ্য পেতে আজই যোগ দিন — একদম বিনামূল্যে!</p>
            </div>

            <form @submit.prevent="submit" class="register-form">
                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">পুরো নাম</label>
                    <div class="input-wrapper" :class="{ 'has-error': form.errors.name }">
                        <div class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <input
                            id="name"
                            type="text"
                            class="form-input"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="আপনার পুরো নাম লিখুন"
                        />
                    </div>
                    <p v-if="form.errors.name" class="form-error">{{ form.errors.name }}</p>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">ইমেইল ঠিকানা</label>
                    <div class="input-wrapper" :class="{ 'has-error': form.errors.email }">
                        <div class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <input
                            id="email"
                            type="email"
                            class="form-input"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="আপনার ইমেইল লিখুন"
                        />
                    </div>
                    <p v-if="form.errors.email" class="form-error">{{ form.errors.email }}</p>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">পাসওয়ার্ড</label>
                    <div class="input-wrapper" :class="{ 'has-error': form.errors.password }">
                        <div class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <input
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            class="form-input"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                            placeholder="কমপক্ষে ৮ অক্ষর"
                        />
                        <button type="button" class="input-toggle" @click="showPassword = !showPassword" tabindex="-1">
                            <svg v-if="!showPassword" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                    <!-- Password strength -->
                    <div v-if="form.password" class="strength-bar-wrap">
                        <div class="strength-track">
                            <div class="strength-fill" :style="{ width: (passwordStrength.level / 4 * 100) + '%', background: passwordStrength.color }"></div>
                        </div>
                        <span class="strength-label" :style="{ color: passwordStrength.color }">{{ passwordStrength.label }}</span>
                    </div>
                    <p v-if="form.errors.password" class="form-error">{{ form.errors.password }}</p>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">পাসওয়ার্ড নিশ্চিত করুন</label>
                    <div class="input-wrapper" :class="{ 'has-error': form.errors.password_confirmation }">
                        <div class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <input
                            id="password_confirmation"
                            :type="showConfirm ? 'text' : 'password'"
                            class="form-input"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="আবার পাসওয়ার্ড লিখুন"
                        />
                        <button type="button" class="input-toggle" @click="showConfirm = !showConfirm" tabindex="-1">
                            <svg v-if="!showConfirm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                    <p v-if="form.errors.password_confirmation" class="form-error">{{ form.errors.password_confirmation }}</p>
                </div>

                <!-- Terms -->
                <p class="terms-text">
                    অ্যাকাউন্ট তৈরি করে আপনি আমাদের <a href="#" class="terms-link">সেবার শর্তাবলী</a> এবং <a href="#" class="terms-link">গোপনীয়তা নীতি</a>-তে সম্মত হচ্ছেন।
                </p>

                <!-- Submit -->
                <button type="submit" class="btn-submit" :disabled="form.processing">
                    <span v-if="form.processing" class="btn-spinner">
                        <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.25"/><path d="M12 2a10 10 0 019.95 9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                    </span>
                    <span v-else>অ্যাকাউন্ট তৈরি করুন</span>
                </button>
            </form>

            <!-- Divider -->
            <div class="auth-divider">
                <span>অথবা</span>
            </div>

            <!-- Login link -->
            <div class="auth-footer">
                <p>ইতোমধ্যে অ্যাকাউন্ট আছে? <Link :href="route('login')" class="auth-footer-link">লগইন করুন</Link></p>
            </div>

            <!-- Back to shop -->
            <div class="back-to-shop">
                <Link href="/" class="back-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    দোকানে ফিরে যান
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
.register-page {
    animation: fadeSlideUp 0.35s ease-out;
}
@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Header */
.register-header { margin-bottom: 24px; }
.register-title {
    font-size: 24px;
    font-weight: 700;
    color: #111;
    letter-spacing: -0.3px;
    margin-bottom: 8px;
    line-height: 1.3;
}
.register-subtitle {
    font-size: 13.5px;
    color: #6b7280;
    line-height: 1.6;
}

/* Form */
.register-form { display: flex; flex-direction: column; gap: 16px; }
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}

/* Input */
.input-wrapper {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    transition: all 0.2s;
    overflow: hidden;
}
.input-wrapper:focus-within {
    border-color: #148a47;
    box-shadow: 0 0 0 3px rgba(20,138,71,0.08);
}
.input-wrapper.has-error {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239,68,68,0.06);
}
.input-icon {
    display: flex;
    align-items: center;
    padding: 0 0 0 14px;
    color: #9ca3af;
    flex-shrink: 0;
}
.input-icon svg { width: 18px; height: 18px; }
.input-wrapper:focus-within .input-icon { color: #148a47; }
.form-input {
    flex: 1;
    padding: 12px 14px;
    border: none;
    outline: none;
    font-size: 14px;
    color: #111;
    background: transparent;
    font-family: inherit;
}
.form-input::placeholder { color: #c0c5cc; }
.input-toggle {
    display: flex;
    align-items: center;
    padding: 0 14px;
    background: none;
    border: none;
    cursor: pointer;
    color: #9ca3af;
    transition: color 0.2s;
}
.input-toggle:hover { color: #374151; }
.input-toggle svg { width: 18px; height: 18px; }
.form-error {
    font-size: 12px;
    color: #ef4444;
    margin: 0;
    font-weight: 500;
}

/* Strength */
.strength-bar-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 2px;
}
.strength-track {
    flex: 1;
    height: 4px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
}
.strength-fill {
    height: 100%;
    border-radius: 4px;
    transition: all 0.3s;
}
.strength-label {
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

/* Terms */
.terms-text {
    font-size: 12px;
    color: #9ca3af;
    line-height: 1.7;
    margin: 0;
}
.terms-link {
    color: #148a47;
    text-decoration: none;
    font-weight: 500;
}
.terms-link:hover { text-decoration: underline; }

/* Submit */
.btn-submit {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, #148a47, #0d5c2e);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s;
    font-family: inherit;
    position: relative;
    overflow: hidden;
}
.btn-submit::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, transparent, rgba(255,255,255,0.1));
    opacity: 0;
    transition: opacity 0.25s;
}
.btn-submit:hover:not(:disabled)::before { opacity: 1; }
.btn-submit:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(20,138,71,0.3);
}
.btn-submit:active:not(:disabled) { transform: translateY(0); }
.btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }
.btn-spinner { display: flex; align-items: center; justify-content: center; }
.btn-spinner svg { width: 22px; height: 22px; animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

/* Divider */
.auth-divider {
    display: flex;
    align-items: center;
    gap: 16px;
    margin: 20px 0;
}
.auth-divider::before,
.auth-divider::after {
    content: ''; flex: 1; height: 1px; background: #e5e7eb;
}
.auth-divider span { font-size: 12px; color: #9ca3af; font-weight: 500; }

/* Footer */
.auth-footer { text-align: center; }
.auth-footer p { font-size: 13.5px; color: #6b7280; margin: 0; }
.auth-footer-link {
    color: #148a47;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
}
.auth-footer-link:hover { color: #0d5c2e; text-decoration: underline; }

/* Back to shop */
.back-to-shop { margin-top: 18px; text-align: center; }
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: #9ca3af;
    text-decoration: none;
    transition: color 0.2s;
}
.back-link:hover { color: #148a47; }
.back-link svg { width: 14px; height: 14px; }
</style>
