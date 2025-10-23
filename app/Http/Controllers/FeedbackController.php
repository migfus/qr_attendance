<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class FeedbackController extends Controller {
    public function index() {
        return Inertia::render('feedbacks/(Index)', [
            'page_title' => 'Feedback',
        ]);
    }
}
