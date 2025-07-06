<?php
namespace App\Mail;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorSuspendedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vendor;

    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
    }

    public function build()
    {
        return $this->subject('Your Vendor Account Has Been Suspended!')
                    ->markdown('emails.vendor.suspended');
    }
}
