package com.example.simex_movil.ui

data class RequestOffer(
    val origin_id: Int,
    val destination_id: Int,
    val volume_m3: Double?,
    val gross_weight_kg: Double?,
    val comments: String?,
    val responsability: String
)

data class LocationResponse(
    val id: Int,
    val name: String
)

// info para .net para cambiar estado de operacion logistica
data class UpdateLogisticsStatusRequest(
    val status: String
)

// respuesta de la api
data class UpdateStatusResponse(
    val message: String,
    val operationId: Int,
    val newStatus: String,
    val deliveredAt: String?
)

// info para .net para cambiar estado de operacion logistica
data class UpdateLogisticsStatusRequest(
    val status: String
)

// respuesta de la api
data class UpdateStatusResponse(
    val message: String,
    val operationId: Int,
    val newStatus: String,
    val deliveredAt: String?
)