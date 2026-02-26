<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
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
<strong>LEYTE V ELECTRIC COOPERATIVE, INC.</strong><br>
Brgy. San Pablo, Ormoc City, Leyte<br>
Tel: (053) 839-3920 to 3921 | Globe: (053) 561-4466<br>
Email: info@leyeco-v.com.ph | Website: www.leyeco-v.com.ph<br><br>
© {{ date('Y') }} LEYTE V ELECTRIC COOPERATIVE, INC. All rights reserved.
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
