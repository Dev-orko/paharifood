<template>
    <Teleport to="body">
        <div class="notif-container" aria-live="polite">
            <TransitionGroup name="notif">
                <div
                    v-for="n in notifications"
                    :key="n.id"
                    :class="['notif-toast', `notif-${n.type}`]"
                    @click="dismiss(n.id)"
                >
                    <div class="notif-accent"></div>
                    <div class="notif-body">
                        <div class="notif-icon-circle">
                            <span class="notif-icon">{{ n.icon }}</span>
                        </div>
                        <div class="notif-content">
                            <span class="notif-msg">{{ n.message }}</span>
                        </div>
                        <button class="notif-close" @click.stop="dismiss(n.id)" aria-label="Close">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                <path d="M1 1L13 13M1 13L13 1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                    <div class="notif-timer">
                        <div class="notif-progress" :style="{ animationDuration: '1.2s' }"></div>
                    </div>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<script setup>
import { useNotification } from '../composables/useNotification';

const { notifications, dismiss } = useNotification();
</script>

<style scoped>
.notif-container {
    position: fixed;
    bottom: 28px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 99999;
    display: flex;
    flex-direction: column-reverse;
    gap: 10px;
    max-width: 460px;
    width: calc(100% - 32px);
    pointer-events: none;
    align-items: center;
}

.notif-toast {
    pointer-events: all;
    width: 100%;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    box-shadow:
        0 20px 60px rgba(0, 0, 0, 0.10),
        0 4px 16px rgba(0, 0, 0, 0.06),
        inset 0 0 0 1px rgba(0, 0, 0, 0.06);
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.notif-toast:hover {
    transform: translateY(-2px);
    box-shadow:
        0 24px 72px rgba(0, 0, 0, 0.14),
        0 6px 20px rgba(0, 0, 0, 0.08),
        inset 0 0 0 1px rgba(0, 0, 0, 0.06);
}

/* Left accent strip */
.notif-accent {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 5px;
    background: linear-gradient(180deg, #27ae60 0%, #1b6e3d 100%);
}

/* Content row */
.notif-body {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 18px 14px 22px;
}

/* Icon circle */
.notif-icon-circle {
    width: 40px;
    height: 40px;
    min-width: 40px;
    border-radius: 50%;
    background: rgba(39, 174, 96, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.notif-icon {
    font-size: 20px;
    line-height: 1;
}

/* Message */
.notif-content {
    flex: 1;
    min-width: 0;
}

.notif-msg {
    font-size: 14.5px;
    font-weight: 600;
    color: #1a3a2a;
    line-height: 1.45;
    letter-spacing: 0.01em;
}

/* Close */
.notif-close {
    flex-shrink: 0;
    background: rgba(0, 0, 0, 0.05);
    border: none;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(0, 0, 0, 0.35);
    cursor: pointer;
    transition: all 0.2s;
}

.notif-close:hover {
    background: rgba(0, 0, 0, 0.1);
    color: #1e272e;
}

/* Timer / Progress */
.notif-timer {
    height: 3px;
    background: rgba(39, 174, 96, 0.1);
}

.notif-progress {
    height: 100%;
    background: linear-gradient(90deg, #27ae60, #1b6e3d);
    border-radius: 0 0 14px 14px;
    animation: notifShrink linear forwards;
}

@keyframes notifShrink {
    from { width: 100%; }
    to   { width: 0%; }
}

/* ─── Transitions ─── */
.notif-enter-active {
    animation: notifIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.notif-leave-active {
    animation: notifOut 0.3s cubic-bezier(0.4, 0, 1, 1) forwards;
}

.notif-move {
    transition: transform 0.3s ease;
}

@keyframes notifIn {
    0% {
        opacity: 0;
        transform: translateY(30px) scale(0.94);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes notifOut {
    0% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    100% {
        opacity: 0;
        transform: translateY(20px) scale(0.92);
    }
}

/* ─── Mobile ─── */
@media (max-width: 480px) {
    .notif-container {
        bottom: 16px;
        max-width: calc(100% - 24px);
    }

    .notif-body {
        padding: 14px 14px 12px 18px;
        gap: 10px;
    }

    .notif-icon-circle {
        width: 34px;
        height: 34px;
        min-width: 34px;
    }

    .notif-icon {
        font-size: 17px;
    }

    .notif-msg {
        font-size: 13.5px;
    }
}
</style>
