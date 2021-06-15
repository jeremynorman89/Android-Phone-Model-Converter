<div class="field is-grouped is-grouped-centered">

    <div class="file is-primary has-name is-boxed">
        <label class="file-label">
            <input class="file-input" type="file" name="file" wire:model="file" accept=".csv">
            <span class="file-cta">
      <span class="file-icon">
        <i class="fas fa-cloud-upload-alt"></i>
      </span>
      <span class="file-label">
        Choose File
      </span>
    </span>
            @if($file)
                <span class="file-name">
             {{ $file->getClientOriginalName() }}
        </span>
            @endif
        </label>
    </div>
</div>
