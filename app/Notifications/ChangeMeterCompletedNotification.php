<?php

namespace App\Notifications;

use App\Models\ChangeMeterRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;
use App\Services\SignatureService;

class ChangeMeterCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $changeMeterRequest;
    protected $signatureService;

    public function __construct(ChangeMeterRequest $changeMeterRequest,  SignatureService $signatureService)
    {
        $this->changeMeterRequest = $changeMeterRequest;
        $this->signatureService = $signatureService;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Generate PDF
        $pdf = $this->generatePDF();
        
        return (new MailMessage)
            ->subject('Change Meter Request Completed - ' . $this->changeMeterRequest->control_no)
            ->from(config('mail.from.address'), 'LEYTE V ELECTRIC COOPERATIVE, INC.')
            ->greeting('Dear ' . $this->changeMeterRequest->first_name . ' ' . $this->changeMeterRequest->last_name . ',')
            ->line('We are pleased to inform you that your change meter request has been successfully completed.')
            ->line('')
            ->line('**Request Details:**')
            ->line('• Control Number: **' . $this->changeMeterRequest->control_no . '**')
            ->line('• Account Number: **' . $this->changeMeterRequest->account_number . '**')
            ->line('• Old Meter Number: ' . $this->changeMeterRequest->old_meter_no)
            ->line('• New Meter Number: **' . $this->changeMeterRequest->new_meter_no . '**')
            ->line('• Date Installed: ' . ($this->changeMeterRequest->date_time_acted ? date('F d, Y h:i A', strtotime($this->changeMeterRequest->date_time_acted)) : 'N/A'))
            ->line('')
            ->line('Please find attached your official change meter request document for your records.')
            ->attachData($pdf->output(), 'LEYECO-V_Change_Meter_' . $this->changeMeterRequest->control_no . '.pdf', [
                'mime' => 'application/pdf',
            ])
            ->line('')
            ->line('Should you have any questions or concerns, please feel free to contact our office.')
            ->line('')
            ->line('Thank you for your continued cooperation and patronage.')
            ->salutation('Sincerely,');
    }

    protected function generatePDF()
    {
        $change_meter_request = $this->changeMeterRequest;

        // Fetch coordinates
        $coordinates = DB::connection('sqlSrvBilling')
            ->table('Consumers Table')
            ->where('Accnt No', $change_meter_request->account_number)
            ->select('latitude', 'longitude')
            ->first();

        $change_meter_request->latitude = $coordinates->latitude ?? null;
        $change_meter_request->longitude = $coordinates->longitude ?? null;

        // Get signature data if it exists
        $signatureResponse = $this->signatureService->getSignatures($change_meter_request->id);
        $signatures = $signatureResponse['success'] ? collect($signatureResponse['data']) : collect();
        $change_meter_request->signatures = $signatures;

        view()->share('data', $change_meter_request);
        
        return PDF::loadView('service_connect_order.change_meter.consumers_copy_cm_request_pdf');
    }
}