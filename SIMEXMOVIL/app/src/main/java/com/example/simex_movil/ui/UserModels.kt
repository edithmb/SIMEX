package com.example.simex_movil.ui

//Recibe los datos del servidor (GET)
data class UserResponse(
    val id: Int,
    val firstName: String,
    val lastName: String,
    val email: String,
    val phoneNumber: String?,
    val passwordHash: String?,
    val isActive: Boolean,
    val fileName: String?,
    val encryptionKey: String?
)

//Envia los datos actualizados (PUT)
data class UserProfileRequest(
    val id: Int,
    val firstName: String,
    val lastName: String,
    val email: String,
    val phoneNumber: String?,
    val passwordHash: String?,
    val isActive: Boolean = true
)

// datos dni
data class DniRecordRequest(
    val entityId: Int,
    val entityType: String,
    val fileName: String,
    val filePath: String,
    val encryptionKey: String
)