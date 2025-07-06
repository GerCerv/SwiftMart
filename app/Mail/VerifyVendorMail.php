<?php

namespace App\Mail;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $vendor;

    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
    }

    public function build()
    {
        $verificationUrl = route('vendor.verification.verify', [
            'token' => $this->vendor->verification_token
        ]);

        return $this->subject('Verify Your Vendor Account')
                   ->markdown('emails.vendor-verification')
                   ->with([
                       'vendor' => $this->vendor,
                       'verificationUrl' => $verificationUrl
                   ]);
    }
}