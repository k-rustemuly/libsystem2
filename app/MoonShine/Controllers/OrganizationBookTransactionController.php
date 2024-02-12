<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers;

use App\Models\OrganizationBookTransaction;
use MoonShine\MoonShineRequest;
use MoonShine\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class OrganizationBookTransactionController extends MoonShineController
{
    public function accept(MoonShineRequest $request): Response
    {
        $organizationId = session('selected_admin')?->organization_id;
        if($organizationId) {
            $transaction = new OrganizationBookTransaction(['organization_id' => $organizationId]);
            $transaction = $transaction->find($request->id);
            if(is_null($transaction->returned_date) && $transaction->received_date <= $request->returned_date) {
                $transaction->returned_date = $request->returned_date;
                $transaction->comment = $request->comment;
                $transaction->save();
                return response()
                    ->json(
                        [
                            'message' => __('moonshine::ui.saved'),
                            'type' => 'success'
                        ]
                    );
            }
        }
        return response()
            ->json(
                [
                    'message' => __('moonshine::ui.errors.book_accept_error'),
                    'messageType' => 'error'
                ]
            );
    }
}
