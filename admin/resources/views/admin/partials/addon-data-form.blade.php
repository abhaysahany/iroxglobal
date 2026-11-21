@php
    $addon = $addon ?? null;
    $fields = [
        'my_direct' => ['label' => 'My Direct', 'integer' => true],
        'total_team' => ['label' => 'Total Team', 'integer' => true],
        'global_members' => ['label' => 'Global Members', 'integer' => true],
        'total_withdrawal' => ['label' => 'Total Withdrawal', 'integer' => false],
        'p2p_wallet' => ['label' => 'P2P Wallet', 'integer' => false],
        'rank_income' => ['label' => 'Rank Income', 'integer' => false],
        'daily_income' => ['label' => 'Daily Income', 'integer' => false],
        'referral_income' => ['label' => 'Referral Income', 'integer' => false],
        'global_rec_income' => ['label' => 'Global Rec Income', 'integer' => false],
        'global_rec_star_income' => ['label' => 'Global Rec Star Income', 'integer' => false],
    ];
@endphp

@if ($addon)
    <input type="hidden" name="id" value="{{ $addon->id }}">
@endif

<div class="form-grid">
    <div class="form-group">
        <label for="memberid">Member ID</label>
        <input id="memberid" name="memberid" type="text" value="{{ old('memberid', $addon?->memberid) }}" required>
        <span id="memMsg"></span>
        @error('memberid') <span class="error-text">{{ $message }}</span> @enderror
    </div>

    @foreach ($fields as $name => $field)
        <div class="form-group">
            <label for="{{ $name }}">{{ $field['label'] }}</label>
            <input id="{{ $name }}" name="{{ $name }}" type="number" min="0"
                step="{{ $field['integer'] ? '1' : '0.01' }}"
                value="{{ old($name, $addon?->{$name} ?? 0) }}" required>
            @error($name) <span class="error-text">{{ $message }}</span> @enderror
        </div>
    @endforeach
</div>
