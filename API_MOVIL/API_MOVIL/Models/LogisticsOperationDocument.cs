using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class LogisticsOperationDocument
{
    public int Id { get; set; }

    public int LogisticsOperationId { get; set; }

    public int DocumentTypeId { get; set; }

    public string? FileUrl { get; set; }

    public string? FileName { get; set; }

    public string? Status { get; set; }

    public bool? IsAdHoc { get; set; }

    public string? CustomName { get; set; }

    public DateTime? UploadedAt { get; set; }

    public DateTime? CreatedAt { get; set; }

    public virtual DocumentType DocumentType { get; set; } = null!;

    public virtual LogisticsOperation LogisticsOperation { get; set; } = null!;
}
