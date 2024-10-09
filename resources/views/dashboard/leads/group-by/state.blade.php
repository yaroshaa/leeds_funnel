@forelse($items as $state => $leads)
    <table class="table table-dark rounded overflow-hidden">
        <tbody>
        <tr class="table-active fw-bold text-center">
            <th scope="row" colspan="5">{{ $state }}</th>
        </tr>
        @foreach($leads->groupBy('date') as $date => $lead)
            @foreach($lead as $item)
                <tr>
                    @if ($loop->first)
                        <th rowspan="{{ $lead->count() }}" class="text-center"
                            style="width: 90px; vertical-align: middle;">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</th>
                    @endif
                    <td style="width: 120px;">{{ $item->deal }}</td>
                    <td>{{ optional($item->user)->name ?? 'n/a' }}</td>
                    <td style="width: 40px;" class="text-center">{{ $item->credits }}</td>
                    <td style="width: 80px;">{{ $item->created_at->format('H:i:s') }}</td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
@empty
    @includeIf('partials.nothing-found')
@endforelse
