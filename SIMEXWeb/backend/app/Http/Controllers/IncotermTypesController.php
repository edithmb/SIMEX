<?php

namespace App\Http\Controllers;

use App\Models\IncotermType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Classes\Utilitat;

class IncotermTypesController extends Controller
{     /**
         * Display a listing of the resource.
         */
        public function index()
        {
            try {
                $incotermTypes = IncotermType::with('incoterms:id,incoterm_type_id,order_num')
                    ->get(['id', 'code', 'name']);

                return response()->json($incotermTypes);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error fetching incoterm types: ' . $e->getMessage()], 500);
            }
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(Request $request)
        {
            try {
                $request->validate([
                    'code' => 'required|string',
                    'name' => 'required|string',
                ]);

                $incotermType = IncotermType::create($request->all());

                return response()->json($incotermType, 201);
            } catch (QueryException $e) {
                $body = Utilitat::queryExceptionResponse($e);
                return response()->json($body, 422);
            }
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, string $id)
        {
            try {
                $request->validate([
                    'code' => 'required|string',
                    'name' => 'required|string',
                ]);

                $incotermType = IncotermType::findOrFail($id);
                $incotermType->update($request->all());

                return response()->json($incotermType);
            } catch (QueryException $e) {
                $body = Utilitat::queryExceptionResponse($e);
                return response()->json($body, 422);
            }
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(string $id)
        {
            try {
                $incotermType = IncotermType::findOrFail($id);
                $incotermType->delete();

                return response()->json(null, 204);
            } catch (QueryException $e) {
                $body = Utilitat::queryExceptionResponse($e);
                return response()->json($body, 422);
            }
        }
    }
