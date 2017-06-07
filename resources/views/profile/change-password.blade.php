<div class="form-container">
    <h1 class="form-title has-text-centered">Change password</h1>
    <div class="form-content">
        <form class="form" action="{{ route('password.change') }}" method="post">
            {{ csrf_field() }}

            @component('components.input', [
                'name' => 'current_password',
                'type' => 'password',
                'placeholder' => 'Current password',
                'icon' => 'key',
            ])
            @endcomponent

            @component('components.input', [
                'name' => 'new_password',
                'type' => 'password',
                'placeholder' => 'New password',
                'icon' => 'key',
            ])
            @endcomponent

            @component('components.input', [
                'name' => 'new_password_confirmation',
                'type' => 'password',
                'placeholder' => 'Confirm password',
                'icon' => 'key',
            ])
            @endcomponent

            <div class="field">
                <p class="control">
                    <button class="change-password button is-primary is-outlined is-fullwidth" :disabled="form.errors.any()">Change</button>
                </p>
            </div>
        </form>
    </div>
</div>
