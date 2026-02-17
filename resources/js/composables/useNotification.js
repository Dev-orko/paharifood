import { ref } from 'vue';

const notifications = ref([]);
let idCounter = 0;

export function useNotification() {
    const notify = ({ message, type = 'success', duration = 1200, icon = null }) => {
        const id = ++idCounter;

        // Auto-assign icon based on type if not provided
        if (!icon) {
            const icons = {
                success: '✅',
                error: '❌',
                warning: '⚠️',
                info: 'ℹ️',
                cart: '🛒',
                wishlist: '❤️',
            };
            icon = icons[type] || '🔔';
        }

        notifications.value.push({ id, message, type, icon });

        // Auto-dismiss
        setTimeout(() => {
            dismiss(id);
        }, duration);
    };

    const dismiss = (id) => {
        const idx = notifications.value.findIndex((n) => n.id === id);
        if (idx !== -1) {
            notifications.value.splice(idx, 1);
        }
    };

    return {
        notifications,
        notify,
        dismiss,
    };
}
