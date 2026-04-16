package com.example.simex_movil.network

import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

object RetrofitClient {

    val BASE_URL = "http://simex6-backend-a0lkj2-5b243c-51-83-192-177.traefik.me/api/"

    private const val DOTNET_BASE_URL = "http://10.0.1.7:5000/api/"

    val apiService: LaravelApiService by lazy {
        Retrofit.Builder()
            .baseUrl(BASE_URL)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
            .create(LaravelApiService::class.java)
    }

    val dotNetApiService: DotNetApiService by lazy {
        Retrofit.Builder()
            .baseUrl(DOTNET_BASE_URL)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
            .create(DotNetApiService::class.java)
    }

}