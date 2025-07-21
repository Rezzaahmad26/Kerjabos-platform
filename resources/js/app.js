import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'ap1',
    forceTLS: true
});


if (window.userId) {
    window.Echo.private(`chat.${window.userId}`)
        .listen('MessageSent', (e) => {
            console.log('Pesan baru diterima:', e);

            const container = document.getElementById('chat-messages');
            if (container) {
                const message = document.createElement('div');
                message.classList.add('mb-2', 'text-sm');

                const strong = document.createElement('strong');
                strong.textContent = e.sender_id === window.userId ? 'You: ' : 'Pengirim: ';
                strong.classList.add(e.sender_id === window.userId ? 'text-indigo-600' : 'text-gray-700');

                message.appendChild(strong);
                message.append(' ' + e.message);
                container.appendChild(message);
                container.scrollTop = container.scrollHeight;
            }
        });
}
