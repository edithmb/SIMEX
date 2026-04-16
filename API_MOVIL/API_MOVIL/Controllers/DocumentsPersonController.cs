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
    }
}
