@props(['url'])
<tr>
<td class="header">
<table class="header-table">
    <tr>
        <td class="header-logo-left">
            @php
                $logoPath = public_path('images/logo.png');
                $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
            @endphp
            @if($logoData)
                <img src="data:image/png;base64,{{ $logoData }}" alt="LEYECO V Logo">
            @endif
        </td>
        <td class="header-content">
            <div>
                <h1>LEYTE V ELECTRIC COOPERATIVE, INC.</h1>
                <p>
                    Brgy. San Pablo, Ormoc City, Leyte<br>
                    <span>Tel: (053) 839-3920 to 3921 | Email: info@leyeco-v.com.ph</span>
                </p>
            </div>
        </td>
        <td class="header-logo-right">
            @php
                $isoPath = public_path('images/iso.png');
                $isoData = file_exists($isoPath) ? base64_encode(file_get_contents($isoPath)) : '';
            @endphp
            @if($isoData)
                <img src="data:image/png;base64,{{ $isoData }}" alt="ISO Certification">
            @endif
        </td>
    </tr>
</table>
<div class="header-border"></div>
</td>
</tr>
