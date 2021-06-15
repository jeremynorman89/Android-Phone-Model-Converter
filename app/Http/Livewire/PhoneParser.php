<?php

namespace App\Http\Livewire;

use App\Phone;
use Carbon\Carbon;
use Illuminate\Support\Str;
use League\Csv\AbstractCsv;
use League\Csv\HTMLConverter;
use League\Csv\Reader;
use League\Csv\Writer;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class PhoneParser extends Component
{
    use WithFileUploads;

    /** @var TemporaryUploadedFile */
    public $file;

    public $contents;

    public $table;

    public function render()
    {
        return view('livewire.phone-parser', [
            'lastUpdate' => Phone::latest()->first()->updated_at->diffForHumans(),
            'contents' => $this->contents,
            'file' => $this->file,
            'table' => $this->table,
        ]);
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => 'mimetypes:text/plain,text/csv', // 1MB Max
        ]);

        $contents = $this->file->get();

        $csv = Reader::createFromString($contents);
        $delimiter = preg_quote($csv->getDelimiter(), '/');

        foreach (Phone::all() as $phone) {

            if (!$phone->hasValidModelName()) continue;

            $model = preg_quote($phone->model, '/');

            $pattern = "(^|$delimiter)($model)($|$delimiter)";

            $friendlyName = preg_replace(
                '/[' . $csv->getEscape() . $csv->getEnclosure() . $csv->getDelimiter() . ']/',
                '',
                $phone->getFriendlyName()
            );

            $contents = preg_replace("/$pattern/m", "$1{$friendlyName}$3", $contents);
        }

        $this->contents = $contents;

        $this->createTable();
    }

    private function createTable()
    {
        $this->table = (new HTMLConverter())
            ->convert(Reader::createFromString($this->contents)->getRecords());
    }

    public function download()
    {
        return response()->streamDownload(function () {
            echo $this->contents;
        }, $this->file->getClientOriginalName(), ['Content-Type' => 'text/csv']);
    }
}
