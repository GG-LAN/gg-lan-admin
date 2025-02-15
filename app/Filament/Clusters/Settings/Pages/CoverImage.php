<?php
namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Models\CoverImage as ModelsCoverImage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CoverImage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'fas-panorama';

    protected static string $view = 'filament.clusters.settings.pages.cover-image';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = 4;

    public array $data = [];

    public bool $fileHasChanged = false;

    public function getTitle(): string | Htmlable
    {
        return __("Settings");
    }

    public static function getNavigationLabel(): string
    {
        return __("Cover Image");
    }

    public function mount(): void
    {
        $coverImage = ModelsCoverImage::firstOrCreate();

        $this->form->fill([
            "image" => $coverImage->path,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make("image")
                    ->disk("public")
                    ->image()
                    ->previewable(true)
                    ->downloadable()
                    ->maxSize(10000)
                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                        $manager = new ImageManager(new Driver());

                        $coverImage = $manager->read($file);

                        $coverImage->toWebp()->save(Storage::disk("public")->path("cover.webp"));

                        $this->fileHasChanged = true;

                        return "cover.webp";
                    }),
            ])
            ->statePath("data");
    }

    public function update(): void
    {
        $coverImage = ModelsCoverImage::first();

        if ($coverImage->path != null && $this->fileHasChanged) {
            Storage::disk("public")->delete($coverImage->path);
        }

        $coverImage->path = "/" . $this->form->getState()["image"];

        $coverImage->save();

        Notification::make()
            ->title(__("responses.setting.updated"))
            ->success()
            ->send();
    }
}
