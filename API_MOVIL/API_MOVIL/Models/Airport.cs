using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class Airport
{
    public int Id { get; set; }

    public string Code { get; set; } = null!;

    public string Name { get; set; } = null!;

    public int CityId { get; set; }

    public virtual City City { get; set; } = null!;
}
