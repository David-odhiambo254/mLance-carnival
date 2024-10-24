@foreach ($formData as $data)
    <tr>
        <td>{{ __($data->name) }}</td>
        @foreach ($packages as $key => $package)
            @php
                $pricingData = @collect(@$package->gig_pricings)->first()->pricing_data;
            @endphp
            <td>
                <div class="gig-price__item-number">
                    @if ($data->type == 'text')
                        <input class="form--control" name="{{ $data->label }}[]"
                            value="{{ @$pricingData->{$data->label} }}" @if ($data->is_required == 'required') required @endif
                            placeholder="{{ keyToTitle($data->label) }}">
                    @elseif($data->type == 'textarea')
                        <textarea class="form--control" name="{{ $data->label }}[]" @if ($data->is_required == 'required') required @endif
                            placeholder="{{ keyToTitle($data->label) }}">{{ @$pricingData->{$data->label} }}</textarea>
                    @elseif($data->type == 'email')
                        <input type="email" class="form--control" name="{{ $data->label }}[]"
                            value="{{ is_object(@$pricingData->{$data->label}) ? '' : (string) @$pricingData->{$data->label} }}"
                            @if ($data->is_required == 'required') required @endif
                            placeholder="{{ keyToTitle($data->label) }}">
                    @elseif($data->type == 'select')
                        <select class="form--control form-select select2-basic" data-minimum-results-for-search="-1"
                            name="{{ $data->label }}[]" @if ($data->is_required == 'required') required @endif>
                            <option value="">@lang('Select One')</option>
                            @foreach ($data->options as $item)
                                <option value="{{ $item }}" @selected($item == @$pricingData->{$data->label})>{{ __($item) }}
                                </option>
                            @endforeach
                        </select>
                    @elseif($data->type == 'checkbox')
                        @foreach ($data->options as $option)
                            <div class="form-check">
                                <input class="form-check-input"
                                    id="{{ $data->label }}_{{ titleToKey($option) }}_{{ titleToKey($package->name) }}"
                                    name="{{ $data->label }}[{{ $key }}][]" type="checkbox"
                                    value="{{ $option }}" @checked(in_array($option, @$pricingData->{$data->label} ?? []))>
                                <label class="form-check-label"
                                    for="{{ $data->label }}_{{ titleToKey($option) }}_{{ titleToKey($package->name) }}">{{ $option }}</label>
                            </div>
                        @endforeach
                    @elseif($data->type == 'radio')
                        @foreach ($data->options as $option)
                            <div class="form-check">
                                <input class="form-check-input"
                                    id="{{ $data->label }}_{{ titleToKey($option) }}_{{ titleToKey($package->name) }}"
                                    name="{{ $data->label }}[{{ $key }}]" type="radio"
                                    value="{{ $option }}" @checked($option == @$pricingData->{$data->label})>
                                <label class="form-check-label"
                                    for="{{ $data->label }}_{{ titleToKey($option) }}_{{ titleToKey($package->name) }}">{{ $option }}</label>
                            </div>
                        @endforeach
                    @elseif($data->type == 'number')
                        <input type="number" class="form--control" name="{{ $data->label }}[]"
                            value="{{ @$pricingData->{$data->label} }}" step="any"
                            @if ($data->is_required == 'required') required @endif
                            placeholder="{{ keyToTitle($data->label) }}">
                    @elseif($data->type == 'url')
                        <input type="url" class="form--control" name="{{ $data->label }}[]"
                            value="{{ @$pricingData->{$data->label} }}"
                            @if ($data->is_required == 'required') required @endif
                            placeholder="{{ keyToTitle($data->label) }}">
                    @elseif($data->type == 'datetime')
                        <input type="datetime-local" class="form--control" name="{{ $data->label }}[]"
                            value="{{ is_object(@$pricingData->{$data->label}) ? '' : @$pricingData->{$data->label} }}"
                            @if ($data->is_required == 'required') required @endif
                            placeholder="{{ keyToTitle($data->label) }}">
                    @elseif($data->type == 'date')
                        <input type="date" class="form--control" name="{{ $data->label }}[]"
                            value="{{ @$pricingData->{$data->label} }}"
                            @if ($data->is_required == 'required') required @endif
                            placeholder="{{ keyToTitle($data->label) }}">
                    @elseif($data->type == 'time')
                        <input type="time" class="form--control" name="{{ $data->label }}[]"
                            value="{{ @$pricingData->{$data->label} }}"
                            @if ($data->is_required == 'required') required @endif
                            placeholder="{{ keyToTitle($data->label) }}">
                    @endif
                </div>
            </td>
        @endforeach
    </tr>
@endforeach
