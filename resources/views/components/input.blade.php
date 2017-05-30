<div class="field">
    <p class="control has-icon">
        <input class="input {{ $errors->has("$name") ? 'is-danger' : '' }}"
            :class="{
                'is-danger': form.errors.has('{{ $name }}'),
                'is-cleared': !form.errors.has('{{ $name }}')
            }"
            @if(! empty($clearAllErrors))
                @keyup="form.errors.clear()"
            @endif
            name="{{ $name }}"
            type="{{ $type or 'text' }}"
            placeholder="{{ $placeholder or ucfirst($name) }}" v-model="form.{{ $name }}"
            value="{{ str_contains($name, 'password') ? '' : old("$name") }}"
            {{ isset($autofocus) ? 'autofocus' : '' }}
            {{ isset($required) ? $required : 'required' }}>
        <span class="icon is-small">
            <i class="fa fa-{{ $icon }}"></i>
        </span>
    </p>
    <p class="help is-danger" v-if="form.errors.has('{{ $name }}')" v-text="form.errors.get('{{ $name }}')">
        @if($errors->has("$name"))
            {{ $errors->first("$name") }}
        @endif
    </p>
</div>
