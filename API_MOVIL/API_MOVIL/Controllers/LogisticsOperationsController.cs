using API_MOVIL.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Authorization;
using System.Security.Claims;
using Microsoft.EntityFrameworkCore; 

namespace API_MOVIL.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    [Authorize] 
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
            // suario token
            var userToken = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userToken)) return Unauthorized("Token inválido.");
            int currentUserId = int.Parse(userToken);

            // que no este vacio
            if (string.IsNullOrWhiteSpace(request.Status))
                return BadRequest("El estado no puede estar vacío.");

            // buscar estado en tabla para no guardar textos inventados
            string requestedStatus = request.Status.ToLower().Trim();
            var trackingStep = await _context.TrackingSteps
                .FirstOrDefaultAsync(ts => ts.Name.ToLower() == requestedStatus);

            if (trackingStep == null)
                return BadRequest($"El estado '{request.Status}' no existe en la tabla de pasos logísticos.");

            // buscar operacion y relaciones para asociar con icoterm
            var operation = await _context.LogisticsOperations
                .Include(o => o.CommercialOffer)
                    .ThenInclude(co => co.Incoterm)
                .Include(o => o.CommercialOffer)
                    .ThenInclude(co => co.ClientRequest)
                .FirstOrDefaultAsync(o => o.Id == id);

            if (operation == null)
                return NotFound($"No hay operación logística con id {id}");

            // validar incoterm y responsabilidad
            var offer = operation.CommercialOffer;
            if (offer?.Incoterm != null && offer?.ClientRequest != null)
            {
                string responsability = offer.ClientRequest.Responsability; // "BUYER" o "SELLER"
                int incotermTypeId = offer.Incoterm.IncotermTypeId;

                // Comprueba si este paso corresponde a este incoterm y a esta responsabilidad
                bool isStepValidForIncoterm = await _context.Incoterms.AnyAsync(i =>
                    i.IncotermTypeId == incotermTypeId &&
                    i.Responsability == responsability &&
                    i.TrackingStepId == trackingStep.Id);

                if (!isStepValidForIncoterm)
                {
                    return StatusCode(403, "Este estado no está permitido para la responsabilidad actual según el Incoterm.");
                }
            }

            // actualiza estado
            operation.Status = trackingStep.Name;
            operation.UpdatedAt = DateTime.UtcNow;

            // guarda fecha si es el ultimo paso
            if (requestedStatus == "unloading at destination")
            {
                operation.CompletedAt = DateTime.UtcNow;
            }

            // guarda cambios
            await _context.SaveChangesAsync();

            return Ok(new
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