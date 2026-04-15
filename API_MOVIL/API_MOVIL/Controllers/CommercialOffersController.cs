using API_MOVIL.Models;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using System.Security.Claims;

namespace API_MOVIL.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    [Authorize]
    public class CommercialOffersController : ControllerBase
    {
        private readonly Simex06Context _context;

        public CommercialOffersController(Simex06Context context)
        {
            _context = context;
        }

        [HttpPut("{id}/status")]
        public async Task<IActionResult> ChangeOfferStatus(int id, [FromBody] UpdateOfferStatusRequest request)
        {
            // buscar oferta en id
            var offer = await _context.CommercialOffers.FindAsync(id);

            if(offer == null)
                return NotFound($"No existe ningun presupuesto con id {id}");

            // validar que se mande un estado correcto
            string newStatus = request.Status.ToLower();
            if (newStatus != "accepted" && newStatus != "rejected")
                return BadRequest("Just accepted or rejected");

            // actualizar campos
            offer.Status = newStatus;
            offer.UpdatedAt = DateTime.UtcNow;

            var userToken = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userToken)) return Unauthorized("invalid token");
            offer.UpdatedBy = int.Parse(userToken);

            //offer.UpdatedBy = 1;//pruebas

            // si recahza debe de decir proque
            if (newStatus == "rejected")
            {
                if (string.IsNullOrWhiteSpace(request.RejectionReason))
                    return BadRequest("write a reason");

                offer.RejectionReason = request.RejectionReason;
            } else
            {
                offer.RejectionReason = null;
            }

            // guardar en bd 
            await _context.SaveChangesAsync();

            return Ok( new
            {
                Message = $"¡Éxito! Presupuesto {id} actualizado a '{newStatus}'.",
                OfferId = offer.Id,
                CurrentStatus = offer.Status,
                Reason = offer.RejectionReason
            });

        }
    }

    public class UpdateOfferStatusRequest
    {
        public string Status { get; set; } = string.Empty;
        public string? RejectionReason { get; set; }
    }


}
