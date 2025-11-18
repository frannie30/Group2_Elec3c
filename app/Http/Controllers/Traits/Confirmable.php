<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait Confirmable
{
    /**
     * If the incoming request is not yet confirmed, render the confirmation view.
     * Call this at the top of your `store` or `update` controller methods.
     *
     * Usage example in a controller method:
     *
     *     $confirm = $this->confirmStep($request, 'Confirm Create', 'Are you sure?');
     *     if ($confirm) return $confirm; // user needs to confirm
     *
     * When the confirmation form is submitted it will include a hidden
     * `confirmed` field and repost to the same URL, so the controller can
     * proceed with normal validation and persistence.
     *
     * @param Request $request
     * @param string $title
     * @param string|null $message
     * @param string|null $cancelUrl
     * @return \Illuminate\Contracts\View\View|null
     */
    public function confirmStep(Request $request, string $title = 'Please confirm', ?string $message = null, ?string $cancelUrl = null)
    {
        if ($request->has('confirmed')) {
            return null; // user already confirmed, proceed
        }

        $inputs = $request->except(['_token']);

        $actionUrl = $request->fullUrl();

        return view('shared.confirm-action', [
            'title' => $title,
            'message' => $message ?? 'Are you sure you want to continue with these changes?',
            'actionUrl' => $actionUrl,
            'cancelUrl' => $cancelUrl ?? url()->previous(),
            'inputs' => $inputs,
        ]);
    }
}
