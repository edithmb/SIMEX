package com.example.simex_movil.ui

data class PaginatedOperationsResponse(
    val data: List<Tracking>
)

// La operación logística
data class Tracking(
    val id: Int,
    val reference: String,
    val status: String,
    val eta: String?, // Con interrogación porque puede venir nulo
    val client: ClienteMinimo?,
    val commercialOffer: CommercialOfferMinimo?
)

//información extra que Laravel nos manda dentro
data class ClienteMinimo(
    val company_name: String
)

data class CommercialOfferMinimo(
    val clientRequest: ClientRequestMinimo?
)

data class ClientRequestMinimo(
    val origin: LocationResponse?,
    val destination: LocationResponse?
)