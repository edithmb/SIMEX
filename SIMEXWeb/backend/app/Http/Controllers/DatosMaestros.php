<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Carrier;
use App\Models\City;
use App\Models\ContainerType;
use App\Models\Country;
use App\Models\Port;
use App\Models\ShippingLine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DatosMaestros extends Controller
{
    private array $tableMap = [
        'countries'      => Country::class,
        'cities'         => City::class,
        'ports'          => Port::class,
        'airports'       => Airport::class,
        'shipping_lines' => ShippingLine::class,
        'carriers'       => Carrier::class,
        'container_types'=> ContainerType::class,
    ];

    private array $validationRules = [
        'countries'      => ['name' => 'required|string|max:50'],
        'cities'         => ['name' => 'required|string|max:50', 'country_id' => 'required|integer|exists:countries,id'],
        'ports'          => ['name' => 'required|string|max:50', 'city_id' => 'required|integer|exists:cities,id'],
        'airports'       => ['code' => 'required|string|max:5', 'name' => 'required|string|max:120', 'city_id' => 'required|integer|exists:cities,id'],
        'shipping_lines' => ['name' => 'required|string|max:50', 'city_id' => 'required|integer|exists:cities,id'],
        'carriers'       => ['name' => 'required|string|max:50', 'city_id' => 'required|integer|exists:cities,id'],
        'container_types'=> ['type_name' => 'required|string|max:50'],
    ];

    private array $withRelations = [
        'cities'         => ['country:id,name'],
        'ports'          => ['city:id,name'],
        'airports'       => ['city:id,name'],
        'shipping_lines' => ['city:id,name'],
        'carriers'       => ['city:id,name'],
    ];

    private function resolveModel(string $tabla): string
    {
        abort_unless(array_key_exists($tabla, $this->tableMap), 404, "Tabla '$tabla' no encontrada.");
        return $this->tableMap[$tabla];
    }

    public function index(string $tabla): JsonResponse
    {
        $model = $this->resolveModel($tabla);
        $relations = $this->withRelations[$tabla] ?? [];

        $data = $model::with($relations)->get();

        return response()->json($data);
    }

    public function store(Request $request, string $tabla): JsonResponse
    {
        $model = $this->resolveModel($tabla);
        $rules = $this->validationRules[$tabla] ?? [];

        $validated = $request->validate($rules);
        $record = $model::create($validated);

        $relations = $this->withRelations[$tabla] ?? [];
        if ($relations) {
            $record->load($relations);
        }

        return response()->json($record, 201);
    }

    public function update(Request $request, string $tabla, int $id): JsonResponse
    {
        $model = $this->resolveModel($tabla);
        $rules = $this->validationRules[$tabla] ?? [];

        $record = $model::findOrFail($id);
        $validated = $request->validate($rules);
        $record->update($validated);

        $relations = $this->withRelations[$tabla] ?? [];
        if ($relations) {
            $record->load($relations);
        }

        return response()->json($record);
    }

    public function destroy(string $tabla, int $id): JsonResponse
    {
        $model = $this->resolveModel($tabla);
        $record = $model::findOrFail($id);
        $record->delete();

        return response()->json(null, 204);
    }
}
