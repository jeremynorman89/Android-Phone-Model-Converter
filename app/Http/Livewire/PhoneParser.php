<?php

namespace App\Http\Livewire;

use App\Phone;
use Illuminate\Support\Collection;
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

    public function render()
    {
        return view('livewire.phone-parser', [
            'lastUpdate' => Phone::latest()->first()->updated_at->diffForHumans(),
            'contents' => $this->contents,
            'file' => $this->file,
            'table' => $this->generateTable(),
        ]);
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => 'mimetypes:text/plain,text/csv', // 1MB Max
        ]);

        $uploadedCsv = Reader::createFromString($this->file->get());
        $generatedCsv = $this->prepareNewCsvBasedOnFile($uploadedCsv);
        $phones = $this->getPhones();

        $generatedCsv->addFormatter(function($record) use ($phones) {
            return array_map(function($cell) use ($phones) {
                return !empty($phones[$cell]) ? $phones[$cell] : $cell;
            }, $record);
        });

        foreach ($uploadedCsv->getRecords() as $record) {
            $generatedCsv->insertOne($record);
        }

        $this->contents = $generatedCsv->toString();
    }

    private function getPhones(): Collection
    {
        return Phone::all()->filter(function($phone) {
            return $phone->hasValidModelName();
        })->pluck('friendly_name', 'model');
    }

    private function prepareNewCsvBasedOnFile(Reader $csv): Writer
    {
        $newCsv = Writer::createFromString();
        $newCsv->setDelimiter($csv->getDelimiter());
        $newCsv->setEnclosure($csv->getEnclosure());
        $newCsv->setEscape($csv->getEscape());
        return $newCsv;
    }

    private function generateTable()
    {
        if (empty($this->contents)) return null;

        $reader = Reader::createFromString($this->contents);

        return (new HTMLConverter())
            ->table("table is-striped is-hoverable is-fullwidth")
            ->convert($reader->getRecords());
    }

    public function download()
    {
        return response()->streamDownload(function () {
            echo $this->contents;
        }, $this->file->getClientOriginalName(), ['Content-Type' => 'text/csv']);
    }
}
