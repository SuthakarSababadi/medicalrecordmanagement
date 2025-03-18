<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    public function mutateFormDataBeforeCreate(array $data): array
    {
        self::checkIfRecordExist($data); // Pass the form data to the check function

        return $data;
    }

    public function mutateFormDataBeforeSave(array $data): array
    {

        self::checkIfRecordExist($data); // Pass the form data to the check function

        return $data;
    }

    public function checkIfRecordExist(array $data)
    {
        $formattedDate = Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');

        $exists = static::getModel()::where('employee_id', $data['employee_id'])
            ->where('category_id', $data['category_id'])
            ->where('date', $formattedDate)
            ->exists();

        // Halt the process if the record already exists
        if ($exists) {
            // Show notification to the user
            Notification::make()
                ->title('Appointment Exists')
                ->danger()
                ->body('An appointment with this employee, category, and date already exists.')
                ->send();

            // Throw a validation exception to stop the save process
            throw ValidationException::withMessages([
                'appointment' => 'An appointment with this employee, category, and date already exists.',
            ]);
        }

    }
}
