using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class IncotermType
{
    public int Id { get; set; }

    public string? Code { get; set; }

    public string? Name { get; set; }

    public virtual ICollection<Incoterm> Incoterms { get; set; } = new List<Incoterm>();
}
