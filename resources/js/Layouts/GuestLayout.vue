<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="auth-wrapper">
        <!-- Left Info Panel -->
        <div class="auth-side">
            <div class="auth-side-inner">
                <!-- Brand -->
                <Link href="/" class="auth-brand">
                    <div class="auth-logo">🏔️</div>
                    <div class="auth-logo-text">
                        <span class="brand-bn">পাহাড়ি স্বাদ</span>
                        <span class="brand-en">Hill Taste</span>
                    </div>
                </Link>

                <h2 class="auth-headline">পাহাড়ের স্বাদ, আপনার ঘরের দোরগোড়ায়</h2>
                <p class="auth-desc">বান্দরবান, রাঙামাটি ও খাগড়াছড়ির পাহাড়ি এলাকা থেকে সংগ্রহ করা ১০০% প্রাকৃতিক ও খাঁটি পণ্য। সরাসরি পাহাড়ি কৃষকদের কাছ থেকে আপনার কাছে।</p>

                <!-- Info Boxes -->
                <div class="info-boxes">
                    <div class="info-box">
                        <div class="info-box-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div class="info-box-content">
                            <h4>১০০% খাঁটি পণ্যের নিশ্চয়তা</h4>
                            <p>প্রতিটি পণ্য সরাসরি পাহাড়ি উৎস থেকে সংগ্রহ করা এবং মান যাচাই করা।</p>
                        </div>
                    </div>
                    <div class="info-box">
                        <div class="info-box-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div class="info-box-content">
                            <h4>সারাদেশে হোম ডেলিভারি</h4>
                            <p>ঢাকায় ২-৩ দিন এবং ঢাকার বাইরে ৩-৫ দিনের মধ্যে ডেলিভারি।</p>
                        </div>
                    </div>
                    <div class="info-box">
                        <div class="info-box-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div class="info-box-content">
                            <h4>নিরাপদ পেমেন্ট সিস্টেম</h4>
                            <p>বিকাশ, নগদ, রকেট ও ক্যাশ অন ডেলিভারি — আপনার সুবিধামতো পেমেন্ট করুন।</p>
                        </div>
                    </div>
                    <div class="info-box">
                        <div class="info-box-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div class="info-box-content">
                            <h4>পাহাড়ি কৃষকদের সহায়তা</h4>
                            <p>আপনার প্রতিটি ক্রয় সরাসরি পাহাড়ি কৃষক পরিবারগুলোকে সহায়তা করে।</p>
                        </div>
                    </div>
                </div>

                <!-- Trust badge -->
                <div class="trust-strip">
                    <div class="trust-item">
                        <span class="trust-num">৫০০০+</span>
                        <span class="trust-label">সন্তুষ্ট গ্রাহক</span>
                    </div>
                    <div class="trust-divider"></div>
                    <div class="trust-item">
                        <span class="trust-num">১০০+</span>
                        <span class="trust-label">পাহাড়ি পণ্য</span>
                    </div>
                    <div class="trust-divider"></div>
                    <div class="trust-item">
                        <span class="trust-num">৩</span>
                        <span class="trust-label">পার্বত্য জেলা</span>
                    </div>
                </div>
            </div>

            <!-- Background shapes -->
            <div class="side-bg-shapes">
                <div class="bg-shape bg-shape-1"></div>
                <div class="bg-shape bg-shape-2"></div>
                <div class="bg-shape bg-shape-3"></div>
                <div class="bg-leaf bg-leaf-1">🌿</div>
                <div class="bg-leaf bg-leaf-2">🍃</div>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="auth-main">
            <div class="auth-form-container">
                <!-- Mobile brand -->
                <Link href="/" class="auth-brand-mobile">
                    <div class="auth-logo-sm">🏔️</div>
                    <div class="auth-logo-text-sm">
                        <span class="brand-bn">পাহাড়ি স্বাদ</span>
                        <span class="brand-en">Hill Taste</span>
                    </div>
                </Link>

                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
.auth-wrapper {
    display: flex;
    min-height: 100vh;
    background: #f8faf9;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

/* ═══════ Side Panel ═══════ */
.auth-side {
    display: none;
    position: relative;
    width: 48%;
    background: linear-gradient(160deg, #0a4a23 0%, #0d6b33 30%, #148a47 60%, #0f7a3c 100%);
    padding: 44px 40px;
    overflow: hidden;
}
@media (min-width: 1024px) {
    .auth-side {
        display: flex;
        align-items: center;
        justify-content: center;
    }
}
.auth-side-inner {
    position: relative;
    z-index: 2;
    max-width: 440px;
    width: 100%;
}

/* Brand */
.auth-brand {
    display: flex;
    align-items: center;
    gap: 14px;
    text-decoration: none;
    margin-bottom: 36px;
}
.auth-logo {
    font-size: 38px;
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.12);
    border-radius: 14px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}
.auth-logo-text {
    display: flex;
    flex-direction: column;
}
.brand-bn {
    font-size: 22px;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
    letter-spacing: -0.3px;
}
.brand-en {
    font-size: 11px;
    color: rgba(255,255,255,0.55);
    font-weight: 500;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

/* Headline */
.auth-headline {
    font-size: 26px;
    font-weight: 700;
    color: #fff;
    line-height: 1.35;
    margin-bottom: 12px;
    letter-spacing: -0.3px;
}
.auth-desc {
    font-size: 13.5px;
    color: rgba(255,255,255,0.65);
    line-height: 1.75;
    margin-bottom: 32px;
}

/* ─── Info Boxes ─── */
.info-boxes {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-bottom: 32px;
}
.info-box {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px 18px;
    background: rgba(255,255,255,0.07);
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,0.08);
    backdrop-filter: blur(6px);
    transition: background 0.25s, border-color 0.25s;
}
.info-box:hover {
    background: rgba(255,255,255,0.12);
    border-color: rgba(255,255,255,0.15);
}
.info-box-icon {
    width: 38px;
    height: 38px;
    min-width: 38px;
    background: rgba(255,255,255,0.12);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px;
    color: #a3f0c5;
}
.info-box-icon svg {
    width: 100%;
    height: 100%;
}
.info-box-content h4 {
    font-size: 13.5px;
    font-weight: 600;
    color: #fff;
    margin: 0 0 4px;
    line-height: 1.3;
}
.info-box-content p {
    font-size: 12px;
    color: rgba(255,255,255,0.55);
    line-height: 1.5;
    margin: 0;
}

/* ─── Trust Strip ─── */
.trust-strip {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    padding: 18px 24px;
    background: rgba(255,255,255,0.06);
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,0.08);
}
.trust-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}
.trust-num {
    font-size: 20px;
    font-weight: 700;
    color: #a3f0c5;
    line-height: 1;
}
.trust-label {
    font-size: 11px;
    color: rgba(255,255,255,0.5);
    margin-top: 4px;
    white-space: nowrap;
}
.trust-divider {
    width: 1px;
    height: 32px;
    background: rgba(255,255,255,0.12);
}

/* Background shapes */
.side-bg-shapes {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 1;
}
.bg-shape {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.05);
}
.bg-shape-1 {
    width: 350px; height: 350px;
    top: -100px; right: -80px;
    background: radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 70%);
}
.bg-shape-2 {
    width: 250px; height: 250px;
    bottom: -60px; left: -50px;
    background: radial-gradient(circle, rgba(255,255,255,0.025) 0%, transparent 70%);
}
.bg-shape-3 {
    width: 120px; height: 120px;
    top: 50%; right: 15%;
    background: radial-gradient(circle, rgba(163,240,197,0.08) 0%, transparent 70%);
}
.bg-leaf {
    position: absolute;
    font-size: 28px;
    opacity: 0.15;
}
.bg-leaf-1 {
    top: 12%;
    right: 8%;
    transform: rotate(-25deg);
}
.bg-leaf-2 {
    bottom: 15%;
    right: 20%;
    transform: rotate(15deg);
}

/* ═══════ Main Panel ═══════ */
.auth-main {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 36px 24px;
}
.auth-form-container {
    width: 100%;
    max-width: 420px;
}

/* Mobile brand */
.auth-brand-mobile {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    margin-bottom: 28px;
    justify-content: center;
}
.auth-logo-sm {
    font-size: 30px;
    width: 46px;
    height: 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ecfdf5;
    border-radius: 12px;
}
.auth-logo-text-sm {
    display: flex;
    flex-direction: column;
}
.auth-brand-mobile .brand-bn {
    color: #0d5c2e;
    font-size: 19px;
}
.auth-brand-mobile .brand-en {
    color: #6b7280;
    font-size: 10px;
}
@media (min-width: 1024px) {
    .auth-brand-mobile { display: none; }
}
</style>
