<?php

namespace App\Livewire;

use App\Models\TempWallet;
use Livewire\Component;

class Temp extends Component
{
    public $key = '';
    public $amount = null; // Add this line

    public function render()
    {
        $this->amount = TempWallet::where('user_key', $this->key)->value('amount') ?? 0; // Update this line
        return view('livewire.temp'); // Removed compact('amount') because amount is a public property
    }
}
