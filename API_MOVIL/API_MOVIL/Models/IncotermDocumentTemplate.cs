using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class IncotermDocumentTemplate
{
    public int Id { get; set; }

    public int IncotermId { get; set; }

    public int DocumentTypeId { get; set; }

    public bool? IsMandatory { get; set; }

    public virtual DocumentType DocumentType { get; set; }

    public virtual Incoterm Incoterm { get; set; }
}
