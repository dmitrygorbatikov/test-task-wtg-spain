export function formatLastMessageTime(timestamp) {
    if (!timestamp) return '';

    const date = new Date(timestamp);

    if (isNaN(date.getTime())) {
        return timestamp;
    }

    const now = new Date();
    const diffMs = now - date;

    if (diffMs < 24 * 60 * 60 * 1000) {
        return date.toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' });
    }

    const yesterday = new Date(now);
    yesterday.setDate(yesterday.getDate() - 1);
    if (date.toDateString() === yesterday.toDateString()) {
        return 'вчера';
    }

    if (diffMs < 7 * 24 * 60 * 60 * 1000) {
        return date.toLocaleDateString('ru-RU', { weekday: 'short' });
    }

    return date.toLocaleDateString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: date.getFullYear() === now.getFullYear() ? undefined : '2-digit'
    });
}
