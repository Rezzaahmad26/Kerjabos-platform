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

        <form action="{{ route('chat.store', $receiver->id) }}" method="POST">
            @csrf
            <textarea name="message" rows="3" class="w-full border rounded-lg p-2" placeholder="Type your message..."></textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            <button type="submit" class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded">Send</button>
        </form>
    </div>
</x-app-layout>
