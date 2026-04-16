package com.example.simex_movil.ui

import com.google.gson.annotations.SerializedName // IMPORTANTE PARA EL TRUCO

data class CajaOperacionesCliente(
    val current_page: Int,
    val data: List<LogisticsOperationResponse>,
    val total: Int,
    val last_page: Int
)

// 2. LA OPERACIÓN (Combinamos tus datos y los de ella)
data class LogisticsOperationResponse(
    val id: Int,
    val reference: String,
    val status: String,
    val eta: String?,
    val client: ClienteInfoCliente?,
    @SerializedName("commercial_offer")
    val commercialOffer: OfertaInfoCliente?
)

//Para navegar por el JSON
data class ClienteInfoCliente(
    val company_name: String
)

data class OfertaInfoCliente(
    @SerializedName("client_request")
    val clientRequest: RequestInfoCliente?,
    val price: Double?
)

data class RequestInfoCliente(
    val origin: UbicacionCliente?,
    val destination: UbicacionCliente?,
    val gross_weight_kg: Double?,
    val volume_m3: Double?,
)

data class UbicacionCliente(
    val name: String
)