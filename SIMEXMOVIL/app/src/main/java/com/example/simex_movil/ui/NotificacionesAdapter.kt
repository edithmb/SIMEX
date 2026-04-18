package com.example.simex_movil.ui

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.example.simex_movil.R

class NotificacionesAdapter(
    private var listaNotificaciones: List<Notificacion>,
    private val onNotificacionClick: (Notificacion) -> Unit // Para detectar cuando tocan una
) : RecyclerView.Adapter<NotificacionesAdapter.NotificacionViewHolder>() {

    class NotificacionViewHolder(view: View) : RecyclerView.ViewHolder(view) {
        val titulo: TextView = view.findViewById(R.id.tvTituloNotificacion)
        val mensaje: TextView = view.findViewById(R.id.tvMensajeNotificacion)
        val indicador: View = view.findViewById(R.id.indicadorNoLeido)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): NotificacionViewHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.item_notificacion, parent, false)
        return NotificacionViewHolder(view)
    }

    override fun onBindViewHolder(holder: NotificacionViewHolder, position: Int) {
        val notificacion = listaNotificaciones[position]

        holder.titulo.text = notificacion.title
        holder.mensaje.text = notificacion.message

        // Si no está leída, mostramos el punto rojo. Si ya la leyó, lo escondemos.
        holder.indicador.visibility = if (notificacion.isRead) View.INVISIBLE else View.VISIBLE

        // Le decimos qué hacer cuando toquen esta tarjeta
        holder.itemView.setOnClickListener {
            onNotificacionClick(notificacion)
        }
    }

    override fun getItemCount() = listaNotificaciones.size

    fun actualizarLista(nuevaLista: List<Notificacion>) {
        listaNotificaciones = nuevaLista
        notifyDataSetChanged()
    }
}