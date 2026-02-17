<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Settings</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-2xl bg-white shadow-xl rounded-2xl p-8">

        {{-- Header --}}
        <div class="mb-6 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">
                ⚙️ General Settings
            </h1>
            <p class="text-gray-500 text-sm">
                Manage your website configuration and preferences.
            </p>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-3 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Site Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Site Name
                </label>

                <input
                    type="text"
                    name="site_name"
                    value="{{ $settings->site_name }}"
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 px-4 py-2 text-sm shadow-sm"
                    placeholder="Enter your website name"
                    required
                >
            </div>

            {{-- Site Active Toggle --}}
            <div class="flex items-center justify-between bg-gray-50 border rounded-lg p-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Website Status</h3>
                    <p class="text-xs text-gray-500">Enable or disable the website visibility.</p>
                </div>

                {{-- ✅ FINAL WORKING TOGGLE --}}
                <label class="relative inline-flex items-center cursor-pointer">

                    {{-- Checkbox --}}
                    <input
                        type="checkbox"
                        name="site_active"
                        class="sr-only peer"
                        {{ $settings->site_active ? 'checked' : '' }}
                    >

                    {{-- Track --}}
                    <div class="w-12 h-7 bg-gray-300 rounded-full
                                peer-checked:bg-blue-600
                                transition-colors duration-300">
                    </div>

                    {{-- Moving Circle (sibling of peer → will slide) --}}
                    <span class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full shadow
                                 transition-transform duration-300
                                 peer-checked:translate-x-5">
                    </span>

                </label>
            </div>

            {{-- Submit Button --}}
            <div class="pt-4">
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg shadow-md transition duration-200"
                >
                    Save Settings
                </button>
            </div>
        </form>
    </div>

</body>
</html>
