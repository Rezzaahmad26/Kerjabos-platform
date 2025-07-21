<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 mt-10">
        <h2 class="text-2xl font-bold mb-4">Chat with {{ $receiver->name }}</h2>

        <div class="bg-white p-6 rounded-lg shadow mb-4 max-h-[400px] overflow-y-auto">
            @foreach ($messages as $msg)
                <div class="mb-2 text-sm">
                    <strong class="{{ $msg->sender_id == auth()->id() ? 'text-indigo-600' : 'text-gray-700' }}">
                        {{ $msg->sender->name }}:
                    </strong>
                    {{ $msg->message }}
                </div>
            @endforeach
        </div>

        <form action="{{ route('chat.send', $receiver->id) }}" method="POST">
            @csrf
            <textarea name="message" rows="3" class="w-full border rounded-lg p-2" placeholder="Type your message..."></textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            <button type="submit" class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded">Send</button>
        </form>
    </div>

    <script>
        window.userId = {{ auth()->id() }};
    </script>

    <script type="module">
        import Echo from 'laravel-echo';
        import Pusher from 'pusher-js';

        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'ap1',
            forceTLS: true,
            encrypted: true,
        });

        Echo.private(`chat.${window.userId}`)
            .listen('MessageSent', (e) => {
                console.log('Pesan masuk:', e);

                // Tambahkan pesan ke dalam chat box
                const container = document.querySelector('#chat-messages');
                if (container) {
                    const div = document.createElement('div');
                    div.innerHTML = `<strong class="text-indigo-600">${e.sender_id == window.userId ? 'You' : 'User ' + e.sender_id}:</strong> ${e.message}`;
                    container.appendChild(div);
                    container.scrollTop = container.scrollHeight;
                }
            });
    </script>

</x-app-layout>
