<?php

namespace App\Livewire;

use Livewire\Component;

class SignaturePad extends Component
{
    public $signatureData = '';

    public function updateSignature($dataUrl)
    {
        $this->signatureData = $dataUrl;
        $this->dispatch('signature-updated', $dataUrl);
        \Log::info('Livewire SignaturePad - signature updated', [
            'length' => strlen($dataUrl)
        ]);
    }

    public function clearSignature()
    {
        $this->signatureData = '';
        $this->dispatch('signature-cleared');
    }

    public function getSignatureData()
    {
        return $this->signatureData;
    }

    public function render()
    {
        return view('livewire.signature-pad');
    }
}
