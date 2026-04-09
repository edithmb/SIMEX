using API_MOVIL.Models;
using API_MOVIL.Services;
using Microsoft.AspNetCore.SignalR;

namespace API_MOVIL.Hubs
{
    public class DocumentsPersonHub : Hub
    {
        private readonly Simex06Context _context;
        private readonly EncryptionService _encryptation;
        private readonly IWebHostEnvironment _env;

        public DocumentsPersonHub(Simex06Context context, EncryptionService encryptation, IWebHostEnvironment env)
        {
            _context = context;
            _encryptation = encryptation;
            _env = env;
        }

        public async Task UploadDni(int entityId, string entityType, string fileName, string base64File)
        {
            try
            {
                await Clients.Caller.SendAsync("Receivemessage", "Receiving and encrypting the dni..."); // avisar que llego el archivo
                byte[] originalFile = Convert.FromBase64String(base64File); // convertir el texto a bytes reales

                var result = _encryptation.Encrypt(originalFile);

                string secureFolder = Path.Combine(_env.WebRootPath ?? _env.ContentRootPath, "wwwroot", "encrypted_dnis"); // preparamos nueva carpeta
                if (!Directory.Exists(secureFolder)) Directory.CreateDirectory(secureFolder);

                // nombre con extension .aes
                string uniqueName = Guid.NewGuid().ToString() + ".aes";
                string finalRoute = Path.Combine(secureFolder, uniqueName);

                await File.WriteAllBytesAsync(finalRoute, result.EncryptedFile);

                var newDni = new PersonalDocument
                {
                    PersonalDocumentTypeId = 1,
                    EntityType = entityType,
                    EntityId = entityId,
                    FileName = fileName,
                    FilePath = $"/dnis_seguros/{uniqueName}",
                    FileSizeBytes = result.EncryptedFile.Length,
                    MimeType = "application/octet-stream", // Archivo binario irreconocible
                    IsEncrypted = true,
                    EncryptionKey = result.GeneratedKey,
                    UploadedBy = 1,
                    CreatedAt = DateTime.UtcNow
                };
                
                // actualizar el cambio
                _context.PersonalDocuments.Add(newDni);
                await _context.SaveChangesAsync();

                await Clients.Caller.SendAsync("UploadCompleted", "ID uploaded, encrypted and locked away.", newDni.Id);
            }
            catch (Exception ex)
            {
                await Clients.Caller.SendAsync("UploadedError", $"Security error:{ex.Message}");

            }

        }

    }
}
