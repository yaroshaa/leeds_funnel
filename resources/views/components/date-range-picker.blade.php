<div x-data x-init="window.daterange($refs.dates)" {{ $attributes }}>
    <input type="text" class="form-control" x-ref="dates" name="dates"
           style="width: {{ $width ?? '200px' }}" placeholder="{{ __('Choose dates') }}"
           autocomplete="off" value="{{ request('dates') }}">
</div>
