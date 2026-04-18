package com.example.simex_movil.ui

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.example.simex_movil.R

class OperacionesAdapter (
    private var listaOperaciones: List<LogisticsOperationResponse>,
    private val alHacerClick: (LogisticsOperationResponse) -> Unit

) : RecyclerView.Adapter<OperacionesAdapter.OperacionViewHolder>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): OperacionViewHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.item_tracking, parent, false)
        return OperacionViewHolder(view)
    }

    override fun getItemCount(): Int = listaOperaciones.size

    override fun onBindViewHolder(holder: OperacionViewHolder, position: Int) {
        val operacion = listaOperaciones[position]
        holder.bind(operacion)

        // LE DECIMOS A LA TARJETA QUE ESCUCHE EL CLICK
        holder.itemView.setOnClickListener {
            alHacerClick(operacion) // Lanzamos el aviso con la operación
        }
    }

    fun actualizarLista(nuevaLista: List<LogisticsOperationResponse>) {
        listaOperaciones = nuevaLista
        notifyDataSetChanged()
    }

    inner class OperacionViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        // Buscamos los IDs de tu XML item_tracking
        private val tvTituloOrden: TextView = itemView.findViewById(R.id.tvTituloOrden)
        private val tvBadgeEstado: TextView = itemView.findViewById(R.id.tvBadgeEstado)
        private val tvRutaPrincipal: TextView = itemView.findViewById(R.id.tvRutaPrincipal)
        private val tvOrigen: TextView = itemView.findViewById(R.id.tvOrigen)
        private val tvDestino: TextView = itemView.findViewById(R.id.tvDestino)
        private val tvEta: TextView = itemView.findViewById(R.id.tvEta)

        fun bind(op: LogisticsOperationResponse) {
            // Fíjate que el recorrido por las variables sigue igual,
            // porque los nombres de las variables internas no cambiaron, solo los nombres de las Clases (Moldes).
            val nombreCliente = op.client?.company_name ?: "Cliente Desconocido"

            val puertoOrigen = op.commercialOffer?.clientRequest?.origin?.name ?: "N/A"
            val puertoDestino = op.commercialOffer?.clientRequest?.destination?.name ?: "N/A"

            tvTituloOrden.text = "$nombreCliente — ${op.reference}"
            tvBadgeEstado.text = op.status.uppercase()
            tvRutaPrincipal.text = "$puertoOrigen → $puertoDestino"
            tvOrigen.text = puertoOrigen
            tvDestino.text = puertoDestino
            tvEta.text = op.eta ?: "Por definir"
        }
    }
}