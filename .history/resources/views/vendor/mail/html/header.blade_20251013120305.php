@props(['url'])
<tr>
    <td class="header" style="padding: 25px 0; text-align: center;">
        <a href="{{ $url }}" style="display: inline-block;">
            <img src="{{ asset('vitslogo.png') }}" class="logo" alt="{{ config('app.name') }}" style="width:120px; height:auto; display:block; margin:0 auto;" />
        </a>
    </td>
</tr>
