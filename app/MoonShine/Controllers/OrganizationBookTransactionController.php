<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers;

use App\Http\Requests\MassReceiveBookRequest;
use App\Models\OrganizationBookInventory;
use App\Models\OrganizationBookTransaction;
use App\Models\OrganizationReader;
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

    public function massReceive(MassReceiveBookRequest $request): Response
    {
        $this->toast(__('moonshine::ui.errors.mass_book_receive_error'), 'error');
        $data = $request->validated();
        $organizationId = session('selected_admin')?->organization_id;
        if($organizationId) {
            $receivedDate = $data['date']['received_date'];
            $returnDate = $data['date']['return_date'];
            $userId = $data['user_id'];
            $books = $data['books'];
            $comments = [];
            $inventoryIds = [];
            foreach($books as $k => $v) {
                $inventory_id = $v['inventory_id'];
                $inventoryIds[] = $inventory_id;
                $comments[$inventory_id] = $v['comment'];
            }
            $inventory = new OrganizationBookInventory(['organization_id' => $organizationId]);
            $reader = OrganizationReader::firstOrCreate(
                [
                    'organization_id' => $organizationId,
                    'user_id' => $userId
                ]
            );
            $inventory->whereIn('id', $inventoryIds)
                ->whereNull('transaction_id')
                ->get()
                ->each(function($item) use($reader, $receivedDate, $returnDate, $comments, $organizationId) {
                    $transaction = new OrganizationBookTransaction(['organization_id' => $organizationId]);
                    $transaction->book()->associate($item->book_id);
                    $transaction->recipientable()->associate($reader);
                    $transaction->inventory_id = $item->id;
                    $transaction->received_date = $receivedDate;
                    $transaction->return_date = $returnDate;
                    $transaction->comment = isset($comments[$item->id]) ? $comments[$item->id] : null;
                    $transaction->save();
                });
                $this->toast(__('moonshine::ui.saved'));
        }
        return back();
    }
}
