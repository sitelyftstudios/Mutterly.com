<header class="header">
    <div class="topBetaNote">
        <div class="contain">
            <h4>Beta <?php echo env('APP_VERSION'); ?></h4>
            <a href="{{ route('index.about') }}">What is this?</a>
        </div>
    </div>
    <div class="innerHeader">
        <div class="contain">
            <div class="navbar-brand">
                <h3><a href="{{ route('index.index') }}">Mutterly</a></h3>
            </div>
            <div class="navbar-action">
                <a href="{{  route('index.index') }}" class="btn btn-small">Share a thought</a>
            </div>
        </div>
    </div>
</header>