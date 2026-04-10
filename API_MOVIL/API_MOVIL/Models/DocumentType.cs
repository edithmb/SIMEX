using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class DocumentType
{
    public int Id { get; set; }

    public string Code { get; set; }

    public string Name { get; set; }

    public string Description { get; set; }

    public bool? IsActive { get; set; }

    public virtual ICollection<IncotermDocumentTemplate> IncotermDocumentTemplates { get; set; } = new List<IncotermDocumentTemplate>();

    public virtual ICollection<LogisticsOperationDocument> LogisticsOperationDocuments { get; set; } = new List<LogisticsOperationDocument>();
}
