using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class City
{
    public int Id { get; set; }

    public string Name { get; set; } = null!;

    public int CountryId { get; set; }

    public virtual ICollection<Airport> Airports { get; set; } = new List<Airport>();

    public virtual ICollection<Carrier> Carriers { get; set; } = new List<Carrier>();

    public virtual Country Country { get; set; } = null!;

    public virtual ICollection<Location> Locations { get; set; } = new List<Location>();

    public virtual ICollection<Port> Ports { get; set; } = new List<Port>();

    public virtual ICollection<ShippingLine> ShippingLines { get; set; } = new List<ShippingLine>();
}
