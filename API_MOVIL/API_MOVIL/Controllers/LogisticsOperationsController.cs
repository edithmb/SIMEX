using API_MOVIL.Models;
using Microsoft.AspNetCore.Mvc;

namespace API_MOVIL.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class LogisticsOperationsController : ControllerBase
    {
        readonly Simex06Context _context;

        public LogisticsOperationsController(Simex06Context context)
        {
            _context = context;
        }

        [HttpPut("{id}/status")]
        public async Task<IActionResult> ChangeTrackingStatus(int id, [FromBody] UpdateLogisticsStatusRequest request)
        {
            // buscar operacion logistica
            var operation = await _context.LogisticsOperations.FindAsync(id);

            if (operation == null)
                return NotFound($"no hay con id {id}");

            // estado no vacio
            if (string.IsNullOrWhiteSpace(request.Status))
                return BadRequest("Empty status");

            //actualizar estado
            operation.Status = request.Status;
            operation.UpdatedAt = DateTime.UtcNow;

            // si esta estado completado guardar fecha finalizacion
            string statusLower = request.Status.ToLower();
            if (statusLower == "completed" || statusLower == "delivered" || statusLower == "entregado")
            {
                operation.CompletedAt = DateTime.UtcNow;
            }

            // guardar en bd 
            await _context.SaveChangesAsync();

            return Ok(
                new
                {
                    Message = $"¡Seguimiento actualizado con éxito!",
                    OperationId = operation.Id,
                    NewStatus = operation.Status,
                    DeliveredAt = operation.CompletedAt

                });

        }
    }
    public class UpdateLogisticsStatusRequest
    {
        public string Status { get; set; } = string.Empty;
    }
}
