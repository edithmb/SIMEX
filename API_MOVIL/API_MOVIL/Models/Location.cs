using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class Location
{
    public int Id { get; set; }

    public string Name { get; set; } = null!;

    public int ClientId { get; set; }

    public decimal? Latitude { get; set; }

    public decimal? Longitude { get; set; }

    public int CityId { get; set; }

    public virtual City City { get; set; } = null!;

    public virtual Client Client { get; set; } = null!;

    public virtual ICollection<ClientRequest> ClientRequestDestinations { get; set; } = new List<ClientRequest>();

    public virtual ICollection<ClientRequest> ClientRequestOrigins { get; set; } = new List<ClientRequest>();
}
