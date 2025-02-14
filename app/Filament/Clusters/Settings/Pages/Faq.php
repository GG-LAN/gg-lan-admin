<?php
namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Models\Faq as FaqModel;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class Faq extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'fas-question';

    protected static string $view = 'filament.clusters.settings.pages.faq';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = 3;

    public function getTitle(): string | Htmlable
    {
        return __("FAQ");
    }

    public static function getNavigationLabel(): string
    {
        return __("FAQ");
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionsAction::make(__("Add"))
                ->icon("fas-plus")
                ->color("success")
                ->modalHeading(__("Add a question/response"))
                ->form([
                    TextInput::make("question")
                        ->translateLabel()
                        ->maxLength(255)
                        ->required(),
                    Textarea::make("response")
                        ->translateLabel()
                        ->autosize()
                        ->required(),
                ])
                ->action(function (array $data) {
                    FaqModel::create($data);

                    Notification::make()
                        ->title(__("responses.faqs.created"))
                        ->success()
                        ->send();
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(FaqModel::query())
            ->paginated(false)
            ->selectable()
            ->columns([
                TextColumn::make("question")
                    ->translateLabel(),
                TextColumn::make("response")
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make("edit")
                    ->icon("fas-pen-to-square")
                    ->iconButton()
                    ->modalHeading(__("Edit"))
                    ->fillForm(fn(FaqModel $record): array=> [
                        'question' => $record->question,
                        'response' => $record->response,
                    ])
                    ->form([
                        TextInput::make("question")
                            ->translateLabel()
                            ->maxLength(255)
                            ->required(),
                        Textarea::make("response")
                            ->translateLabel()
                            ->autosize()
                            ->required(),
                    ])
                    ->action(function (array $data, FaqModel $record) {
                        $record->update($data);

                        Notification::make()
                            ->title(__("responses.faqs.updated"))
                            ->success()
                            ->send();
                    }),
                DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
