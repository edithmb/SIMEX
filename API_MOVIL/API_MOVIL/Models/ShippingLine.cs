using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class ShippingLine
{
    public int Id { get; set; }

    public string Name { get; set; } = null!;

    public int CityId { get; set; }

    public virtual City City { get; set; } = null!;
}
