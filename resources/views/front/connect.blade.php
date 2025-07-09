{{-- @section('content') --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Connect') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 bg-white shadow-sm sm:rounded-lg py-10">
            <div class=" overflow-hidden  flex flex-col gap-y-5 ">
                <h2 class="font-bold text-3xl">Connect Topup</h2></div>
                 <div class="flex flex-row justify-between items-center">
                    <div class="flex flex-row gap-x-5 items-center mt-6">
                        <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.4" d="M19 10.2798V17.4298C18.97 20.2798 18.19 20.9998 15.22 20.9998H5.78003C2.76003 20.9998 2 20.2498 2 17.2698V10.2798C2 7.5798 2.63 6.7098 5 6.5698C5.24 6.5598 5.50003 6.5498 5.78003 6.5498H15.22C18.24 6.5498 19 7.2998 19 10.2798Z" fill="#292D32"/>
                            <path d="M22 6.73V13.72C22 16.42 21.37 17.29 19 17.43V10.28C19 7.3 18.24 6.55 15.22 6.55H5.78003C5.50003 6.55 5.24 6.56 5 6.57C5.03 3.72 5.81003 3 8.78003 3H18.22C21.24 3 22 3.75 22 6.73Z" fill="#292D32"/>
                            <path d="M6.96027 18.5601H5.24023C4.83023 18.5601 4.49023 18.2201 4.49023 17.8101C4.49023 17.4001 4.83023 17.0601 5.24023 17.0601H6.96027C7.37027 17.0601 7.71027 17.4001 7.71027 17.8101C7.71027 18.2201 7.38027 18.5601 6.96027 18.5601Z" fill="#292D32"/>
                            <path d="M12.5494 18.5601H9.10938C8.69938 18.5601 8.35938 18.2201 8.35938 17.8101C8.35938 17.4001 8.69938 17.0601 9.10938 17.0601H12.5494C12.9594 17.0601 13.2994 17.4001 13.2994 17.8101C13.2994 18.2201 12.9694 18.5601 12.5494 18.5601Z" fill="#292D32"/>
                            <path d="M19 11.8599H2V13.3599H19V11.8599Z" fill="#292D32"/>
                        </svg>
                            <div>
                                <p class="text-slate-500 text-sm ">Total Connect</p>
                                <h3 class="text-indigo-950 text-xl font-bold">{{ Auth::user()->connect }}</h3>
                            </div>
                    </div>
                    <div class="flex flex-row gap-x-5">
                    @can('topup connect')
                        @php
                            $pendingTopup = Auth::user()->connectTopups()->where('is_paid', false)->latest()->first();
                        @endphp

                        {{-- Jika ada topup yang pending --}}
                        @if($pendingTopup)
                            <div class="p-4 mb-4 bg-yellow-100 text-yellow-800 rounded-lg text-sm font-semibold">
                                Admin sedang memvalidasi permintaan top-up Anda. Mohon tunggu konfirmasi.
                            </div>

                        {{-- Jika connect 0 dan tidak ada topup --}}
                        @elseif(Auth::user()->connect == 0)

                            @if(session('success'))
                                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('connect.topup') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="flex flex-col gap-y-2">
                                    <label class="text-sm text-slate-600 font-semibold">Upload Bukti Transfer (Transfer ke: 1234567890 - BCA)</label>
                                    <input type="file" name="payment_proof" required class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100" />
                                    @error('payment_proof')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                    <button type="submit" class="mt-3 font-bold py-2 px-4 bg-blue-600 text-white rounded-full">
                                        Ajukan Top-Up 10 Connect (Rp100.000)
                                    </button>
                                </div>
                            </form>

                        @endif
                    @endcan
                </div>
                </div>


            </div>
        </div>
    </div>


{{-- @endsection --}}
</x-app-layout>
