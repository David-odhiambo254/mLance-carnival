<div class="w-auto flex-fill">
    <select name="is_published" class="form-control">
        <option value="">@lang('All')</option>
        <option value="1" @selected(request()->is_published == 1)>@lang('Published')</option>
        <option value="0" @selected(request()->is_published != null && request()->is_published == 0)>@lang('Drafted')</option>
    </select>
</div>
