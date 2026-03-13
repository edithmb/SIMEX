using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class Document
{
    public int Id { get; set; }

    public int? DocumentTypeId { get; set; }

    public string EntityType { get; set; } = null!;

    public int EntityId { get; set; }

    public string FileName { get; set; } = null!;

    public string FilePath { get; set; } = null!;

    public long? FileSizeBytes { get; set; }

    public string? MimeType { get; set; }

    public bool IsEncrypted { get; set; }

    public string? EncryptionKey { get; set; }

    public int UploadedBy { get; set; }

    public DateTime CreatedAt { get; set; }

    public virtual User UploadedByNavigation { get; set; } = null!;
}
