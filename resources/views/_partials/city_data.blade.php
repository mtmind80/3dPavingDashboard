@foreach ($zips as $key => $value)
    <tr>
        <td class="tc">{{ $value['city'] }}</td>
        <td class="tc">{{ $value['proposals'] }}</td>
        <td class="tc">{{ $value['work_orders'] ?? '0' }}</td>
        <td class="tc">{{ \App\Helpers\Currency::format($value['sales'] ?? '0.0') }}</td>
    </tr>
@endforeach
