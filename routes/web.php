<?php

use App\Http\Controllers\WhatsappNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Define Twilio credentials as environment variables
// In your .env file:
// TWILIO_AUTH_TOKEN=YOUR_TWILIO_AUTH_TOKEN
// TWILIO_ACCOUNT_SID=YOUR_TWILIO_ACCOUNT_SID
// TWILIO_FROM_NUMBER=+1234567890

Route::get('/send-wa', function () {

    $response = Http::withHeaders([
        'Authorization' => "A5crhvScDqWs4PC8Lo9c",
    ])->post("https://api.fonnte.com/send", [
        // Add 'To' and 'Body' parameters to send a message
        'target' => '6287880433119', // Replace with the recipient's number
        'message' => 'Hello from Iqmam!', // Replace with the message body
    ]);

    // Handle the response
    if ($response->successful()) {
        // Message sent successfully
        return 'Message sent successfully!';
    } else {
        // Handle errors
        return 'Error sending message: ' . $response->status();
    }
});

Route::post('/send-whatsapp', [WhatsappNotification::class, 'send'])
    ->name('whatsapp.send');

