@php
    $currencyFieldName = $name ?? 'currency';
    $selectedCurrency = old($currencyFieldName, $selected ?? '');
    $currencySelectClass = $class ?? 'form-control select2';
@endphp

<select class="{{ $currencySelectClass }}" name="{{ $currencyFieldName }}" {{ !empty($required) ? 'required' : '' }}>
    <option value="">Select Currency</option>
    @foreach (availableCurrencyCodes() as $currency)
        <option value="{{ $currency }}" {{ $selectedCurrency === $currency ? 'selected' : '' }}>
            {{ $currency }}
        </option>
    @endforeach
</select>
