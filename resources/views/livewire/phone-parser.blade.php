<div>
    <h1 class="title">
        Android Phone Model Converter
    </h1>
    <p class="subtitle">
        Upload a CSV file and all fields containing an Android phone model (e.g. SM-G960F) will be replaced
        with their marketing name (e.g. Galaxy S9)
    </p>
    <small>Powered by Google's <a target="_blank" href="https://storage.googleapis.com/play_public/supported_devices.html">
            Supported Devices</a> list</small>
    <small>(last updated {{ $lastUpdate }})</small>

    <section class="section has-text-centered">
        <form wire:submit.prevent="save">

            @include('includes._file_upload')
        </form>
    </section>

    <section class="section">
        <div class="block" wire:loading.flex wire:target="file">
            <progress class="progress is-large is-primary" max="100">15%</progress>
        </div>

        @unless(empty($contents))
            <div class="block">
                <button class="button is-primary is-fullwidth" wire:click="download">
                <span class="icon">
                  <i class="fa fa-file-download"></i>
                </span>
                </button>
            </div>

            <div class="box">
                @if (!empty($table))
                    {!! $table !!}
                @endif
            </div>
        @endunless

    </section>

</div>
