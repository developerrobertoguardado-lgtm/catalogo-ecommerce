<script id="app-alerts-data" type="application/json">
{!! json_encode([
    'success' => session('success') ?? session('status'),
    'error' => session('error'),
    'info' => session('info'),
    'warning' => session('warning'),
    'errors' => $errors->all(),
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>

<noscript>
    @if (session('success') || session('status'))
        <div class="alert alert-success m-3">{{ session('success') ?? session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger m-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</noscript>
