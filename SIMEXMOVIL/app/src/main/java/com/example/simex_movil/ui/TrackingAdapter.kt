package com.example.simex_movil.ui

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.example.simex_movil.R

class TrackingAdapter(
    private var lista: List<Tracking>,
    private val alHacerClick: (Tracking) -> Unit // Para poder entrar al detalle
) : RecyclerView.Adapter<TrackingAdapter.MyViewHolder>() {

    // Clase interna para encontrar los IDs del diseño de la tarjeta
    class MyViewHolder(view: View) : RecyclerView.ViewHolder(view) {
        val tvOrderId: TextView = view.findViewById(R.id.tvOrderId)
        val tvOrigen: TextView = view.findViewById(R.id.tvOrigenPrincipal)
        val tvDestino: TextView = view.findViewById(R.id.tvDestinoPrincipal)
        val tvEstado: TextView = view.findViewById(R.id.tvBadgeEstadoGeneral)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): MyViewHolder {
        val vista = LayoutInflater.from(parent.context).inflate(R.layout.item_tracking, parent, false)
        return MyViewHolder(vista)
    }

    override fun getItemCount(): Int = lista.size

    override fun onBindViewHolder(holder: MyViewHolder, position: Int) {
        val operacion = lista[position]

        // Rellenamos con los datos del modelo Tracking
        holder.tvOrderId.text = operacion.reference
        holder.tvEstado.text = operacion.status.uppercase()

        // Navegamos por el JSON: commercialOffer -> clientRequest -> origin/destination
        val origen = operacion.commercialOffer?.clientRequest?.origin?.name ?: "N/A"
        val destino = operacion.commercialOffer?.clientRequest?.destination?.name ?: "N/A"

        holder.tvOrigen.text = origen
        holder.tvDestino.text = destino

        // Evento de click para ir a gestionar la operación
        holder.itemView.setOnClickListener { alHacerClick(operacion) }
    }

    // Función para refrescar la lista cuando lleguen datos de Laravel
    fun actualizarDatos(nuevaLista: List<Tracking>) {
        this.lista = nuevaLista
        notifyDataSetChanged()
    }
}