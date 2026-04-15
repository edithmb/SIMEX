package com.example.simex_movil.ui

data class Request(
    val origin_id: Int,
    val destination_id: Int,
    val volume_m3: Double,
    val gross_weight_kg: Double,
    val comments: String?,
    val responsability: String
)

data class LocationResponse(
    val id: Int,
    val name: String
)
