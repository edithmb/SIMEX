using API_MOVIL.Models;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace API_MOVIL.Controllers
{
    [Route("api/[controller]")]
    [ApiController]

    public class NotificationsController : Controller
    {
        private readonly Simex06Context _context;
        public NotificationsController(Simex06Context context)
        {
            _context = context;
        }

        [HttpPost("trigger")]
        [AllowAnonymous] // No pide Token porque viene del servidor de Laravel, no de un teléfono
        public async Task<IActionResult> TriggerNewOfferNotification([FromBody] TriggerRequest request)
        {
            //Buscamos a los usuarios que pertenecen a ese cliente
            var usuariosDelCliente = await _context.Users
                .Where(u => u.ClientId == request.ClientId && u.IsActive == true)
                .ToListAsync();

            if (!usuariosDelCliente.Any())
            {
                return Ok(new { success = false, message = "No hay usuarios para este cliente." });
            }

            //Creamos las notificaciones
            foreach (var usuario in usuariosDelCliente)
            {
                var notificacion = new Notification // Asegúrate de que el modelo se llame así
                {
                    UserId = usuario.Id,
                    Title = "¡Nuevo Presupuesto Disponible!",
                    Message = $"Tienes una nueva oferta con referencia {request.Reference} pendiente de revisión.",
                    IsRead = false, // 0 en la base de datos
                    ReferenceId = request.OfferId,
                    CreatedAt = System.DateTime.UtcNow
                };

                _context.Notifications.Add(notificacion);
            }

            // 3. Guardamos todo en SQL Server
            await _context.SaveChangesAsync();

            return Ok(new { success = true, generadas = usuariosDelCliente.Count });
        }

        [Authorize] //Solo entra con su Token
        [HttpGet("unread")]
        public async Task<IActionResult> GetUnreadNotifications()
        {
            // Extraemos el ID del usuario del Token de seguridad
            var userId = int.Parse(User.FindFirst("id")?.Value ?? "0");

            var notificaciones = await _context.Notifications
                .Where(n => n.UserId == userId && n.IsRead == false) // Solo las no leídas
                .OrderByDescending(n => n.CreatedAt) // Las más nuevas arriba
                .ToListAsync();

            return Ok(notificaciones);
        }

        [Authorize] // Protegido
        [HttpPut("{id}/read")]
        public async Task<IActionResult> MarkAsRead(int id)
        {
            var userId = int.Parse(User.FindFirst("id")?.Value ?? "0");

            var notificacion = await _context.Notifications
                .FirstOrDefaultAsync(n => n.Id == id && n.UserId == userId);

            if (notificacion == null)
            {
                return NotFound(new { message = "Notificación no encontrada." });
            }

            // ¡Apagamos el puntito rojo!
            notificacion.IsRead = true;
            await _context.SaveChangesAsync();

            return Ok(new { success = true });
        }
    }

    public class TriggerRequest
    {
        public int ClientId { get; set; }
        public string Reference { get; set; }
        public int OfferId { get; set; }
    }

}

