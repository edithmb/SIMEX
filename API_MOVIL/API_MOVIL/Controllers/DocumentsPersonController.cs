using API_MOVIL.Models;
using API_MOVIL.Services;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Authorization;
using System.Security.Claims;

namespace API_MOVIL.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    [Authorize]
    public class DocumentsPersonController : ControllerBase
    {
        private readonly Simex06Context _context;
        private readonly EncryptionService _encryptation;
        private readonly IWebHostEnvironment _env;

        public DocumentsPersonController(Simex06Context context, EncryptionService encryptation, IWebHostEnvironment env)
        {
            _context = context;
            _encryptation = encryptation;
            _env = env;
        }

        [HttpGet("download/{id}")]
        public async Task<IActionResult> DownloadDni(int id)
        {
            //buscar ficha del doc en la bd
            var document = await _context.PersonalDocuments.FindAsync(id);
            if (document == null) return NotFound("Document doesnt found");

            //buscar el archivo fisico encriptado y sacar nombre de este
            string fileName = Path.GetFileName(document.FilePath);
            string physicalPath = Path.Combine(_env.ContentRootPath, "wwwroot", "encrypted_dnis", fileName);

            if (!System.IO.File.Exists(physicalPath))
                return NotFound($"The physical file doesnt exist at: {physicalPath}");

            try
            {
                byte[] encryptedBytes = await System.IO.File.ReadAllBytesAsync(physicalPath); // leer bytes encriptados
                byte[] decryptedBytes = _encryptation.Decrypt(encryptedBytes, document.EncryptionKey); // el encriptador abre el archivo usando la llave

                // devolver documento limpio para que el navegador lo descrague
                return File(decryptedBytes, "application/octet-stream", document.FileName);
            }
            catch (Exception ex) 
            {
                return StatusCode(500, $"Error al desencriptar: {ex.Message}");
            }
        }

        [HttpPost("record")]
        public async Task<IActionResult> RecordDni([FromBody] DniRecordRequest request)
        {
            try
            {
                //leer token 
                var userToken = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;

                int realUserId = 1;
                if (!string.IsNullOrEmpty(userToken))
                {
                    realUserId = int.Parse(userToken); // Transformamos el "1" (String) a 1 (Int)
                }

                var newDni = new PersonalDocument
                {
                    PersonalDocumentTypeId = 1, // Tipo 1 = DNI
                    EntityType = request.EntityType,
                    EntityId = request.EntityId,
                    FileName = request.FileName,
                    FilePath = request.FilePath,
                    FileSizeBytes = 0, // Como el archivo lo guarda Kotlin, podemos dejarlo en 0 o no usarlo
                    MimeType = "application/octet-stream",
                    IsEncrypted = true,
                    EncryptionKey = request.EncryptionKey,
                    UploadedBy = realUserId,
                    CreatedAt = DateTime.UtcNow
                };

                // guardar cambios
                _context.PersonalDocuments.Add(newDni);
                await _context.SaveChangesAsync();
                return Ok(new { Message = "DNI registrado en BD con éxito", Id = newDni.Id });

            }
            catch(Exception ex) 
            {
                return StatusCode(500, $"Error al guardar en BD: {ex.Message}");
            }
        }

        // DTO para recibir los datos de Android
        public class DniRecordRequest
        {
            public int EntityId { get; set; }
            public string EntityType { get; set; } = string.Empty;
            public string FileName { get; set; } = string.Empty;
            public string FilePath { get; set; } = string.Empty;
            public string EncryptionKey { get; set; } = string.Empty;
        }
    }
}
