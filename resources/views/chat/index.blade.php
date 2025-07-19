<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Chat dengan {{ $receiver->name }}</h2>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
        <div class="h-80 overflow-y-auto border border-gray-200 p-4 mb-4 rounded">
            @forelse($messages as $msg)
                <div class="mb-2 {{ $msg->sender_id == auth()->id() ? 'text-right' : 'text-left' }}">
                    <span class="px-3 py-2 inline-block rounded-lg {{ $msg->sender_id == auth()->id() ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $msg->message }}
                    </span>
                </div>
            @empty
                <p class="text-center text-gray-500">Belum ada pesan.</p>
            @endforelse
        </div>

        <form action="{{ route('chat.send', $receiver->id) }}" method="POST" class="flex gap-3">
            @csrf
            <input type="text" name="message" placeholder="Ketik pesan..." required class="border rounded-lg flex-1 px-4 py-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Kirim</button>
        </form>
    </div>
</x-app-layout>
