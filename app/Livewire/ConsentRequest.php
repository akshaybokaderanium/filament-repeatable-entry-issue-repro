<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use App\Models\ConsentOption;

class ConsentRequest extends SimplePage
{
    use InteractsWithFormActions;
    use InteractsWithForms;

    public $user;

    public Collection $collection;

    public $acceptConsents = [];

    public $errorBags = [];

    public function mount(): void
    {
        $this->user = auth()->user();

        if (!$this->user) {
            abort(403, 'Forbidden User');
        }

        $this->user->collections = $this->user->outstandingConsents();

        if ($this->user->collections->count() < 1) {
            abort(403, 'No required consent');
        }
    }

    public static ?string $title = 'Your consent is required';

    protected static string $view = 'livewire.consent-option-request';

    public function getMaxWidth(): MaxWidth | string | null
    {
        return MaxWidth::SixExtraLarge;
    }

    public static function getSort()
    {
        return 0;
    }

    public static function canView()
    {
        return false;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->user)
            ->schema([
                RepeatableEntry::make('collections')
                    ->label('')
                    ->schema([
                        Section::make(fn (ConsentOption $record) => "{$record->title} v{$record->version}")
                            ->description(function (ConsentOption $record) {
                                $suffix = $this->previousConsents($record->key);
                                $mandatory = $record->is_mandatory ? 'Mandatory' : 'Optional';
                                if ($suffix) {
                                    $mandatory .= " - ( $suffix )";
                                }

                                return $mandatory;
                            })
                            ->icon(fn (ConsentOption $record) => $record->is_mandatory ? 'heroicon-o-check-badge' : 'heroicon-o-question-mark-circle')
                            ->iconColor(fn (ConsentOption $record) => $record->is_mandatory ? 'success' : 'info')
                            ->schema([
                                TextEntry::make('text')->label('')
                                    ->markdown(),
                                ViewEntry::make('acceptConsent')
                                    ->label('')
                                    ->view('infolist.consent-option-checkbox'),
                                // ConsentAcceptForm::make('Agreement Info'),
                                TextEntry::make('updated_at')
                                    ->label('')
                                    ->alignEnd()
                                    ->html()
                                    ->state(function (ConsentOption $record): string {
                                        return new HtmlString('<strong>Last Updated</strong>: ' . $record->updated_at->format('d M Y'));
                                    })
                            ])
                            ->collapsible()
                            ->collapsed(function (ConsentOption $record) {
                                $first = $this->user->collections->first();
                                return  !($first->id === $record->id);
                            })
                            //                                ->persistCollapsed()
                            ->id(fn (ConsentOption $record) => "consent-option-{$record->id}"),
                    ])
                    ->contained(false)
                    ->columns(2)
                    ->columnSpanFull(),
                Actions::make([
                    Action::make('saveConsents')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->before(function (Action $action) {
                            $this->validateConsents($action);
                        })
                        ->action(function (array $data) {
                            $this->acceptConsent();
                        }),
                ])->alignEnd(),

            ])->columns(1);
    }

    public function previousConsents($key)
    {
        if ($this->user->hasPreviousConsents($key)) {
            $lastViewed = $this->user->lastConsentByKey($key);

            return 'Our consent statement has been updated since you last ' . $lastViewed->pivot->accepted ? 'accepted' : 'viewed' . ' ' . $lastViewed->pivot->created_at->diffForHumans();
        }
    }

    public function validateConsents($action)
    {
        $conentIds = [];
        $errorBags = [];
        foreach ($this->acceptConsents as $key => $value) {
            if ((bool)$value['accepted']) {
                $conentIds[] = $key;

                $consentOption = ConsentOption::find($key);
                if ((bool)$consentOption->additional_info) {
                    foreach ($consentOption->fields as $field) {
                        if ((bool)$field['required'] &&  (!isset($value[$field['name']]) || $value[$field['name']] == "")) {
                            $errorBags[$key][$field['name']] = $field['name'] . " field is required";
                        }
                    }
                }
            }
        }

        $this->errorBags = $errorBags;

        $validateMandatoryConsents = $this->user->requiredOutstandingConsentsValidate($conentIds);

        if (!$validateMandatoryConsents || count($errorBags) > 0) {
            Notification::make()
                ->title('Please confirm.!')
                ->body('Please accept all required consent options.')
                ->icon('heroicon-o-check-circle')
                ->color('danger')
                ->send();
            $action->cancel();
        }
    }

    public function acceptConsent()
    {
        $conentIds = [];
        foreach ($this->acceptConsents as $key => $value) {
            if ((bool)$value['accepted']) {
                $conentIds[] = $key;
            }
        }

        $outstandingConsents = $this->user->outstandingConsents();
        foreach ($outstandingConsents as $consentOption) {
            $this->user->consents()
                ->save(
                    $consentOption,
                    [
                        'accepted' => in_array($consentOption->id, $conentIds),
                        'key' => $consentOption->key,
                        'fields' => (bool)$consentOption->additional_info ? $this->acceptConsents[$consentOption->id] : []
                    ]
                );
        }
        Notification::make()
            ->title('Success')
            ->body('Your consent preferences have been saved.')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->send();


        return redirect(request()->session()->get('url.saved'));
    }
}
