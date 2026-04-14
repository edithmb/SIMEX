<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use App\Models\CommercialOffer;
use App\Models\Incoterm;
use App\Models\LogisticsOperation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComercialOfferController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_request_id'   => 'required|integer|exists:client_requests,id',
            'incoterm_id'         => 'required|integer|exists:incoterms,id',
            'origin_port_id'      => 'required|integer|exists:ports,id',
            'destination_port_id' => 'required|integer|exists:ports,id',
            'container_type_id'   => 'required|integer|exists:container_types,id',
            'price'               => 'required|numeric|min:0',
            'valid_until'         => 'required|date',
            'reference'           => 'nullable|string|max:255',
            'comments'            => 'nullable|string',
        ]);

        $reference = $validated['reference']
            ?: 'PR-' . date('Y') . '-' . str_pad(CommercialOffer::count() + 1, 3, '0', STR_PAD_LEFT);

        $offer = CommercialOffer::create([
            ...$validated,
            'reference'  => $reference,
            'comments'   => $validated['comments'] ?? '',
            'client_id'  => ClientRequest::find($validated['client_request_id'])->client_id,
            'status'     => 'draft',
            'created_by' => auth()->id(),
        ]);

        return response()->json($offer, 201);
    }

    public function index(): JsonResponse
    {
        $offers = $this->buildOffersQuery()->paginate(10);
        return response()->json($offers);
    }

    public function mine(): JsonResponse
    {
        $clientId = auth()->user()->client_id;
        $offers = $this->buildOffersQuery()->where('client_id', $clientId)->paginate(10);
        return response()->json($offers);
    }

    public function approve($id): JsonResponse
    {
        $offer = CommercialOffer::with(['incoterm', 'clientRequest'])->findOrFail($id);

        DB::transaction(function () use ($offer) {
            $offer->update(['status' => 'accepted', 'updated_by' => auth()->id()]);

            if (LogisticsOperation::where('commercial_offer_id', $offer->id)->exists()) {
                return;
            }

            $responsability = $offer->clientRequest?->responsability;
            $incotermTypeId = $offer->incoterm?->incoterm_type_id;

            $firstStepName = null;
            if ($responsability && $incotermTypeId) {
                $firstStep = Incoterm::where('incoterm_type_id', $incotermTypeId)
                    ->where('responsability', $responsability)
                    ->orderBy('order_num')
                    ->with('trackingStep:id,name')
                    ->first();
                $firstStepName = $firstStep?->trackingStep?->name;
            }

            $reference = 'OP-' . date('Y') . '-' . str_pad(LogisticsOperation::count() + 1, 3, '0', STR_PAD_LEFT);

            LogisticsOperation::create([
                'reference'           => $reference,
                'commercial_offer_id' => $offer->id,
                'client_id'           => $offer->client_id,
                'status'              => $firstStepName ?? 'preparation',
            ]);
        });

        return response()->json($offer->fresh());
    }

    public function reject(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);
        $offer = CommercialOffer::findOrFail($id);
        $offer->update([
            'status'           => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'updated_by'       => auth()->id(),
        ]);
        return response()->json($offer);
    }

    private function buildOffersQuery()
    {
        return CommercialOffer::select([
                'id',
                'reference',
                'client_request_id',
                'client_id',
                'incoterm_id',
                'origin_port_id',
                'destination_port_id',
                'container_type_id',
                'valid_until',
                'price',
                'status',
                'rejection_reason',
                'comments',
                'created_at',
            ])
            ->with([
                'client:id,company_name',
                'incoterm:id,incoterm_type_id',
                'incoterm.incotermType:id,code,name',
                'originPort:id,name',
                'destinationPort:id,name',
                'containerType:id,type_name',
            ])
            ->orderBy('created_at', 'desc');
    }
}
