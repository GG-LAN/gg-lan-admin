<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.front_url')">
{{-- {{ config('app.name') }} --}}
<img src="{{ asset('logo.png') }}" class="logo" alt="Laravel Logo">
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
