<template>
    <section class="advert-section">
        <div class="container">
            <div class="advert-grid">
                <!-- Ad Banner 1 -->
                <div class="advert-card">
                    <img src="https://images.pexels.com/photos/1435904/pexels-photo-1435904.jpeg?auto=compress&cs=tinysrgb&w=600" alt="বিশেষ অফার" />
                </div>

                <!-- Ad Banner 2 -->
                <div class="advert-card">
                    <img src="https://images.pexels.com/photos/264547/pexels-photo-264547.jpeg?auto=compress&cs=tinysrgb&w=600" alt="পাহাড়ি পণ্য" />
                </div>

                <!-- Ad Banner 3 -->
                <div class="advert-card">
                    <img src="https://images.pexels.com/photos/1407305/pexels-photo-1407305.jpeg?auto=compress&cs=tinysrgb&w=600" alt="নতুন সংগ্রহ" />
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
</script>

<style scoped>
.advert-section {
    padding: 50px 0;
    background: white;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

.advert-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.advert-card {
    width: 100%;
    overflow: hidden;
    border-radius: 8px;
}

.advert-card img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    display: block;
}

/* Responsive Design */
@media (max-width: 992px) {
    .advert-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .advert-card:last-child {
        grid-column: 1 / -1;
    }
}

@media (max-width: 768px) {
    .advert-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .advert-card:last-child {
        grid-column: auto;
    }
    
    .advert-card img {
        height: 200px;
    }
    
    .advert-section {
        padding: 40px 0;
    }
}
</style>
