using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using API_MOVIL.Models;
using Microsoft.AspNetCore.Http; // para IForm file
using Microsoft.AspNetCore.Hosting; // para saber donde guardar archivo

namespace API_MOVIL.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class DocumentsTramiteController : ControllerBase
    {
        private readonly Simex06Context _context;
        private readonly IWebHostEnvironment _env; // ruta carpeta wwwroot

        public DocumentsTramiteController(Simex06Context context, IWebHostEnvironment env)
        {
            _context = context;
            _env = env;
        }

        // subir documento
        [HttpPost("upload")]
        // [fromForm] porque es un documento adjunto no un json
        public async Task<IActionResult> UploadDocument([FromForm] UploadDocumentDto request)
        {
            // validaciones 
            // archivo
            if (request.archive == null || request.archive.Length == 0)
                return BadRequest("No file has been sent.");

            // operacion logistica
            var operation = await _context.LogisticsOperations.FindAsync(request.logisticsOperationId);
            if (operation == null) return NotFound("The logistics operation does not exist.");

            try
            {
                //preparar ruta
                string uploadsFolder = Path.Combine(_env.WebRootPath ?? _env.ContentRootPath, "wwwroot", "uploads");

                // crear carpeta si no existe
                if (!Directory.Exists(uploadsFolder))
                    Directory.CreateDirectory(uploadsFolder);

                // nombre unico para que no se escirba encima de uno que ya hay 
                string uniqueName = Guid.NewGuid().ToString() + "_" + request.archive.FileName;
                string finalRoute = Path.Combine(uploadsFolder, uniqueName);

                //guardar el archivo fisico
                using (var stream = new FileStream(finalRoute, FileMode.Create))
                {
                    await request.archive.CopyToAsync(stream);
                }

                // registro bd
                var newDocument = new LogisticsOperationDocument
                {
                    LogisticsOperationId = request.logisticsOperationId,
                    DocumentTypeId = request.documentTypeId,
                    FileName = request.archive.FileName, // nombre original
                    FileUrl = $"/uploads/{uniqueName}", // ruta para descargarlo
                    CustomName = request.archive.FileName,
                    Status = "UPLOADED",
                    UploadedAt = DateTime.UtcNow,
                    CreatedAt = DateTime.UtcNow
                };

                _context.LogisticsOperationDocuments.Add(newDocument);
                await _context.SaveChangesAsync();

                return Ok(new { message = "File uploaded successfully", document = newDocument });
            }
            catch (Exception ex) {
                return StatusCode(500, $"Internal server error: {ex.Message}");
            }
        }
    }

    public class UploadDocumentDto
    {
        public int logisticsOperationId { get; set; }
        public int documentTypeId { get; set; }
        public IFormFile archive { get; set; }

    }

}
